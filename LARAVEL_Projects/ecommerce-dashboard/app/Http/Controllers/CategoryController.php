<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    //
    public function index()
    {
        $categories = Category::latest()->paginate(10);

        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json([
                'categories' => $categories,
            ]);
        } else {
            return Inertia::render('Categories/Index', [
                'categories' => $categories,
            ]);
        }
    }

    public function create()
    {
        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json([]);
        } else {
            return Inertia::render('Categories/Create');
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|alpha_dash'
        ]);

        $category = Category::create($validated);

        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json(['category' => $category, 'message' => 'Kategori berhasil dibuat!']);
        } else {
            return redirect()->route('categories.index')->with('success', 'Kategori berhasil dibuat!');
        }
    }

    public function edit(Category $category)
    {
        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json([
                'category' => $category,
            ]);
        } else {
            return Inertia::render('Categories/Edit', [
                'category' => $category,
            ]);
        }
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|alpha_dash',
        ]);

        $category->update($validated);

        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json(['category' => $category, 'message' => 'Kategori berhasil diperbarui!']);
        } else {
            return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui!');
        }
    }

    public function destroy(Category $category)
    {
        $category->delete();

        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json(['message' => 'Kategori berhasil dihapus.']);
        } else {
            return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
        }
    }
}