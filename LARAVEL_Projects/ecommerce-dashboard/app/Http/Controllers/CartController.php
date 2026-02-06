<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    //
    public function index($slug)
    {
        $tenant = Tenant::where('slug', $slug)->firstOrFail();

        $cart = session()->get('cart', []);

        return view('store.cart', compact('tenant', 'cart'));
    }

    // 1. Logic Tambah ke Keranjang (Pastikan menyimpan data Tenant)
    public function addToCart($slug, $productId)
    {
        $tenant = Tenant::where('slug', $slug)->firstOrFail();
        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['status' => 'error', 'message' => 'Produk tidak ditemukan'], 404);
        }

        $cart = session()->get('cart', []);

        // Gunakan ID unik gabungan (TenantID_ProductID) agar tidak bentrok
        $cartKey = $tenant->id . '_' . $product->id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']++;
        } else {
            $cart[$cartKey] = [
                "product_id" => $product->id,
                "name" => $product->name,
                "price" => $product->price,
                "image" => $product->image,
                "quantity" => 1,
                // PENTING: Simpan Info Toko untuk Grouping
                "tenant_id" => $tenant->id,
                "tenant_name" => $tenant->name,
                "tenant_slug" => $tenant->slug,
                "tenant_phone" => $tenant->phone ?? "6281234567890" // Nanti ambil dari database user/tenant
            ];
        }

        session()->put('cart', $cart);

        // Hitung total quantity untuk badge
        $totalQty = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil ditambahkan!',
            'total_quantity' => $totalQty
        ]);
    }
    // Update Method Ini (Agar tombol hapus & edit bisa baca Key yang benar)
    public function globalCart()
    {
        $cart = session()->get('cart', []);

        // PENTING: Masukkan Key asli ke dalam data item
        // Agar saat di-grouping, key-nya tidak hilang/berubah
        foreach ($cart as $key => $val) {
            $cart[$key]['key'] = $key;
        }

        // Kelompokkan Barang Berdasarkan Toko
        $cartsByStore = collect($cart)->groupBy('tenant_name');

        return view('cart.global', compact('cartsByStore'));
    }

    // --- FITUR BARU: Tambah / Kurang Quantity ---
// --- FITUR AJAX: Update Quantity ---
    public function changeQuantity(Request $request, $key, $operation)
    {
        $cart = session()->get('cart');

        if (isset($cart[$key])) {
            // 1. Update Logic
            if ($operation == 'plus') {
                $cart[$key]['quantity']++;
            } elseif ($operation == 'minus') {
                $cart[$key]['quantity']--;
            }

            // Simpan Session Sementara
            // Jika quantity 0, jangan unset dulu (kita butuh datanya untuk response), 
            // nanti dihapus setelah hitung-hitungan selesai.
            if ($cart[$key]['quantity'] <= 0) {
                // Tandai untuk dihapus
                $shouldRemove = true;
            } else {
                $shouldRemove = false;
                session()->put('cart', $cart);
            }

            // 2. Hitung Ulang Data untuk JSON Response
            $item = $cart[$key];
            $lineTotal = $item['price'] * $item['quantity'];
            $storeName = $item['tenant_name'];

            // Hitung Subtotal Toko Terkait
            $newStoreSubtotal = 0;
            foreach ($cart as $c) {
                if ($c['tenant_name'] === $storeName && $c['quantity'] > 0) {
                    $newStoreSubtotal += ($c['price'] * $c['quantity']);
                }
            }

            // Hitung Total Badge Global
            $totalQty = 0;
            foreach ($cart as $c) {
                if ($c['quantity'] > 0)
                    $totalQty += $c['quantity'];
            }

            // Hapus jika qty 0
            if ($shouldRemove) {
                unset($cart[$key]);
                session()->put('cart', $cart);
            }

            // 3. Return JSON
            return response()->json([
                'status' => 'success',
                'action' => $shouldRemove ? 'remove' : 'update',
                'key' => $key,
                'new_qty' => $shouldRemove ? 0 : $item['quantity'],
                'new_line_total' => number_format($lineTotal, 0, ',', '.'),
                'new_store_subtotal' => number_format($newStoreSubtotal, 0, ',', '.'),
                'new_global_qty' => $totalQty,
                'store_slug' => \Illuminate\Support\Str::slug($storeName) // ID untuk update UI Subtotal
            ]);
        }

        return response()->json(['status' => 'error'], 404);
    }

    // --- FITUR AJAX: Hapus Item ---
    public function remove($key)
    {
        $cart = session()->get('cart');

        if (isset($cart[$key])) {
            $storeName = $cart[$key]['tenant_name'];
            unset($cart[$key]);
            session()->put('cart', $cart);

            // Hitung Ulang Subtotal Toko
            $newStoreSubtotal = 0;
            foreach ($cart as $c) {
                if ($c['tenant_name'] === $storeName) {
                    $newStoreSubtotal += ($c['price'] * $c['quantity']);
                }
            }

            // Hitung Global Qty
            $totalQty = array_sum(array_column($cart, 'quantity'));

            return response()->json([
                'status' => 'success',
                'action' => 'remove',
                'key' => $key,
                'new_store_subtotal' => number_format($newStoreSubtotal, 0, ',', '.'),
                'new_global_qty' => $totalQty,
                'store_slug' => \Illuminate\Support\Str::slug($storeName)
            ]);
        }

        return response()->json(['status' => 'error'], 404);
    }
}
