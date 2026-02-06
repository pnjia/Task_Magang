<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tenant;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validasi Data dari Pop-up
        $request->validate([
            'tenant_slug' => 'required',
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'customer_address' => 'required|string',
            'items' => 'required|array',
            'invoice_code' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $tenant = Tenant::where('slug', $request->tenant_slug)->firstOrFail();

            // 2. Cek User Login (Opsional)
            $userId = Auth::id(); // Ambil ID user jika sedang login

            // Jika tidak login, kita buatkan user dummy atau biarkan NULL
            // (Sesuaikan dengan settingan database transactions Anda kolom user_id boleh null atau tidak)
            if (!$userId) {
                $emailDummy = $request->customer_phone . '@customer.com';
                // Cek user by email dummy
                $user = User::firstOrCreate(
                    ['email' => $emailDummy],
                    [
                        'name' => $request->customer_name,
                        'password' => Hash::make('password123'),
                        'role' => 'customer',
                        'tenant_id' => $tenant->id,
                    ]
                );
                $userId = $user->id;
            }

            // 3. Simpan Transaksi Utama
            $transaction = Transaction::create([
                'tenant_id' => $tenant->id,
                'user_id' => $userId,
                'invoice_code' => $request->invoice_code,
                'transaction_date' => now(),

                // Data Pembeli (Pastikan kolom ini sudah ditambahkan di migrasi sebelumnya)
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,

                // Keuangan
                'total_amount' => $request->total_price,
                'payment_amount' => 0, // Belum bayar
                'change_amount' => 0,
                'status' => 'unpaid' // Status awal: Belum Bayar
            ]);

            // 4. Simpan Detail Barang
            $cart = session()->get('cart', []);

            foreach ($request->items as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                // Kurangi Stok Produk
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->decrement('stock', $item['quantity']);
                }

                // Hapus Barang Milik Toko Ini Saja dari Keranjang
                if (isset($item['key']) && isset($cart[$item['key']])) {
                    unset($cart[$item['key']]);
                }
            }

            // Update Session Keranjang
            session()->put('cart', $cart);

            DB::commit();

            // 5. Beri Respon SUKSES ke JavaScript
            return response()->json([
                'status' => 'success',
                'message' => 'Transaksi berhasil disimpan'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            // Kirim pesan error ke Console Browser agar mudah dicek
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }
}