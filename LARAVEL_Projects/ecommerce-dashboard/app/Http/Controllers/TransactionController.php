<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Wajib untuk fitur "Batalkan jika error"

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

        return view('transactions.create', compact('products'));
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
                        'product_instance' => $product
                    ];
                }

                // 3. Cek Uang Pembayaran
                if ($request->payment_amount < $totalAmount) {
                    throw new \Exception("Uang pembayaran kurang!");
                }

                // 4. Simpan Header Transaksi
                $transaction = Transaction::create([
                    // tenant_id otomatis diisi Trait
                    'user_id' => Auth::id(),
                    'invoice_code' => 'INV-' . time(), // Contoh: INV-170123456
                    'transaction_date' => now(),
                    'total_amount' => $totalAmount,
                    'payment_amount' => $request->payment_amount,
                    'change_amount' => $request->payment_amount - $totalAmount,
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

                // Sukses!
                return redirect()->route('transactions.create')
                    ->with('success', 'Transaksi Berhasil! Kembalian: Rp ' . number_format((float) $transaction->change_amount, 0, ',', '.'));
            });

        } catch (\Exception $e) {
            // Jika ada error (stok kurang / uang kurang), kembali ke kasir dengan pesan error
            return back()->with('error', $e->getMessage());
        }
    }

    public function index()
    {
        $transactions = Transaction::with('user')->latest('transaction_date')->paginate(10);

        return view('transactions.index', compact('transactions'));
    }


    public function show(Transaction $transaction)
    {
        if ($transaction->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $transaction->load(['details.product', 'user']);

        return view('transactions.show', compact('transaction'));
    }
}