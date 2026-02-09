<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    //
    public function index(Request $request)
    {

        $query = Product::with('category')->latest();

        // Filter berdasarkan nama produk (pencarian)
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('filter_price')) {
            $query->where('price', '<=', $request->filter_price);
        }

        if ($request->filled('filter_category')) {
            $query->where('category_id', $request->filter_category);
        }

        if ($request->filled('filter_stock')) {
            $query->where('stock', '<=', $request->filter_stock);
        }

        $products = $query->paginate(10)->withQueryString();

        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|alpha_dash',
            'category_id' => ['required', Rule::exists('categories', 'id')],
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $filePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $filePath;


        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', 'alpha_dash'],
            'category_id' => ['required', Rule::exists('categories', 'id')],
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // 2. Cek apakah ada upload gambar baru?
        if ($request->hasFile('image')) {

            // Hapus gambar lama jika ada
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            // Simpan gambar baru (Saya perbaiki typo 'productes' jadi 'products')
            $filePath = $request->file('image')->store('products', 'public');

            // Masukkan path gambar baru ke array data yang akan diupdate
            $validated['image'] = $filePath;
        }

        // 3. Update Data (DILAKUKAN DI LUAR IF)
        // Ini agar data tetap terupdate meski tidak ganti gambar
        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        Gate::authorize('delete-product');

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

}


