<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

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

        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json([
                'products' => $products,
                'categories' => $categories,
                'filters' => $request->only(['search', 'filter_price', 'filter_category', 'filter_stock']),
            ]);
        } else {
            return Inertia::render('Products/Index', [
                'products' => $products,
                'categories' => $categories,
                'filters' => $request->only(['search', 'filter_price', 'filter_category', 'filter_stock']),
            ]);
        }
    }

    public function create()
    {
        $categories = Category::all();

        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json([
                'categories' => $categories,
            ]);
        } else {
            return Inertia::render('Products/Create', [
                'categories' => $categories,
            ]);
        }
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

        $product = Product::create($validated);

        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json(['product' => $product, 'message' => 'Produk berhasil ditambahkan!']);
        } else {
            return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
        }
    }

    public function show(Product $product)
    {
        if ($product->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json([
                'product' => $product->load('category'),
            ]);
        } else {
            return Inertia::render('Products/Show', [
                'product' => $product->load('category'),
            ]);
        }
    }

    public function edit(Product $product)
    {
        if ($product->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $categories = Category::all();
        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json([
                'product' => $product,
                'categories' => $categories,
            ]);
        } else {
            return Inertia::render('Products/Edit', [
                'product' => $product,
                'categories' => $categories,
            ]);
        }
    }

    public function update(Request $request, Product $product)
    {
        if ($product->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

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

        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json(['product' => $product, 'message' => 'Produk berhasil diperbarui!']);
        } else {
            return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
        }
    }

    public function destroy(Product $product)
    {
        if ($product->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        Gate::authorize('delete-product');

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json(['message' => 'Produk berhasil dihapus.']);
        } else {
            return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
        }
    }

}


