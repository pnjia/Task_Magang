<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    //
    public function index($slug)
    {
        $tenant = Tenant::where('slug', $slug)->firstOrFail();

        $products = Product::where('tenant_id', $tenant->id)->where('is_active', true)->where('stock', '>', 0)->latest()->paginate(12);

        return view('store.index', compact('tenant', 'products'));
    }

    public function show($slug, $productSlug)
    {
        $tenant = Tenant::where('slug', $slug)->firstOrFail();

        $product = Product::where('tenant_id', $tenant->id)->where('slug', $productSlug)->firstOrFail();

        return view('store.show', compact('tenant', 'product'));
    }
}
