<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('tenant')->where('stock', '>', 0);

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->paginate(12);

        // KEMBALIKAN BARIS INI:
        return view('marketplace', compact('products'));
    }
}
