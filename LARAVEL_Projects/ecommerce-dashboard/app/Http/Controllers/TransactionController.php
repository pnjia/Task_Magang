<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Wajib untuk fitur "Batalkan jika error"
use Inertia\Inertia;

class TransactionController extends Controller
{
    /**
     * Halaman Kasir (POS)
     */
    public function create()
    {
        // Ambil produk yang aktif dan stoknya > 0
        $products = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        return Inertia::render('Transactions/Create', [
            'products' => $products,
        ]);
    }

    /**
     * Proses Simpan Transaksi (Checkout)
     */
    public function store(Request $request)
    {
        // 1. Validasi Data yang dikirim dari Frontend (Alpine.js)
        $request->validate([
            'payment_amount' => 'required|numeric|min:0',
            'cart' => 'required|array|min:1', // Harus ada minimal 1 barang
            'cart.*.id' => 'required|exists:products,id', // Tiap barang harus valid
            'cart.*.qty' => 'required|integer|min:1',
        ]);

        // Gunakan DB::transaction agar atomik (Semua sukses atau semua gagal)
        try {
            return DB::transaction(function () use ($request) {

                $totalAmount = 0;
                $details = [];

                // 2. Loop Keranjang untuk hitung total & validasi stok
                foreach ($request->cart as $item) {
                    $product = Product::lockForUpdate()->find($item['id']); // Kunci baris agar tidak ditabrak transaksi lain

                    if ($product->stock < $item['qty']) {
                        throw new \Exception("Stok {$product->name} tidak cukup!");
                    }

                    // Hitung Subtotal
                    $subtotal = $product->price * $item['qty'];
                    $totalAmount += $subtotal;

                    // Siapkan data detail untuk disimpan nanti
                    $details[] = [
                        'product_id' => $product->id,
                        'quantity' => $item['qty'],
                        'price' => $product->price,
                        'subtotal' => $subtotal,
                        // Kurangi Stok Sekarang
                        'product_instance' => $product,
                    ];
                }

                // 3. Cek Uang Pembayaran
                if ($request->payment_amount < $totalAmount) {
                    throw new \Exception('Uang pembayaran kurang!');
                }

                // 4. Simpan Header Transaksi
                // Status: 'completed' karena sudah dibayar langsung saat checkout kasir
                $transaction = Transaction::create([
                    // tenant_id otomatis diisi Trait
                    'user_id' => Auth::id(),
                    'invoice_code' => 'INV-'.time(), // Contoh: INV-170123456
                    'transaction_date' => now(),
                    'total_amount' => $totalAmount,
                    'payment_amount' => $request->payment_amount,
                    'change_amount' => $request->payment_amount - $totalAmount,
                    'status' => 'completed', // ✅ Kasir membayar langsung, jadi langsung completed
                ]);

                // 5. Simpan Detail & Update Stok Real
                foreach ($details as $detail) {
                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $detail['product_id'],
                        'quantity' => $detail['quantity'],
                        'price' => $detail['price'],
                        'subtotal' => $detail['subtotal'],
                    ]);

                    // Update stok di database
                    $detail['product_instance']->decrement('stock', $detail['quantity']);
                }

                // Sukses! Redirect ke History (bukan Create lagi)
                return redirect()->route('transactions.history')
                    ->with('success', 'Transaksi Berhasil! Invoice: '.$transaction->invoice_code.' | Kembalian: Rp '.number_format((float) $transaction->change_amount, 0, ',', '.'));
            });

        } catch (\Exception $e) {
            // Jika ada error (stok kurang / uang kurang), kembali ke kasir dengan pesan error
            return back()->with('error', $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        // Active Statuses: Pesanan yang sedang berjalan (tidak termasuk completed dan cancelled)
        $query = Transaction::where('tenant_id', Auth::user()->tenant_id)
            ->whereIn('status', ['unpaid', 'paid', 'processing', 'shipped']);

        // Filter berdasarkan invoice (pencarian)
        if ($request->filled('search')) {
            $query->where('invoice_code', 'like', '%'.$request->search.'%');
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        // Filter berdasarkan status
        if ($request->filled('filter_status')) {
            $query->where('status', $request->filter_status);
        }

        $transactions = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Transactions/Index', [
            'transactions' => $transactions,
            'filters' => $request->only(['search', 'date_from', 'date_to', 'filter_status']),
        ]);
    }

    public function history(Request $request)
    {
        // Archive Statuses: Pesanan yang sudah selesai atau dibatalkan
        $query = Transaction::where('tenant_id', Auth::user()->tenant_id)
            ->whereIn('status', ['completed', 'cancelled']);

        // Filter berdasarkan invoice (pencarian)
        if ($request->filled('search')) {
            $query->where('invoice_code', 'like', '%'.$request->search.'%');
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        // Tidak ada filter status untuk history (hanya completed & cancelled)

        $transactions = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('Transactions/History', [
            'transactions' => $transactions,
            'filters' => $request->only(['search', 'date_from', 'date_to']),
        ]);
    }

    public function show(Transaction $transaction)
    {
        if ($transaction->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $transaction->load(['details.product', 'user']);

        return Inertia::render('Transactions/Show', [
            'transaction' => $transaction,
        ]);
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        if ($transaction->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:unpaid,paid,processing,shipped,completed,cancelled',
        ]);

        $transaction->status = $validated['status'];
        $transaction->save();

        return redirect()->route('transactions.index')->with('success', 'Status berhasil diupdate!');
    }

    public function confirmPayment(Transaction $transaction)
    {
        if ($transaction->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $transaction->update([
            'payment_amount' => $transaction->total_amount,
            'change_amount' => 0,
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }

    public function update(Request $request, Transaction $transaction)
    {
        // 1. Cek Hak Akses (Keamanan)
        if ($transaction->tenant_id != Auth::user()->tenant_id) {
            abort(403, 'Anda tidak berhak mengubah pesanan ini.');
        }

        // 2. Validasi Input
        $request->validate([
            'status' => 'required|in:unpaid,paid,processing,shipped,completed,cancelled',
        ]);

        // 3. UPDATE SECARA MANUAL (LEBIH KUAT)
        // Cara ini mem-bypass proteksi $fillable/$guarded yang sering bikin error diam-diam
        $transaction->status = $request->status;
        $transaction->save();

        return back()->with('success', 'Status pesanan berhasil diperbarui menjadi: '.ucfirst($request->status));
    }
}
