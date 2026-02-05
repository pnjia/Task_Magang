<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $totalProducts = Product::count();
        $totalTransactions = Transaction::count();
        $totalRevenue = Transaction::sum('total_amount');

        $lowStockProducts = Product::with('category')->where('stock', '<', 10)->orderBy('stock', 'asc')->limit(5)->get();

        $recentTransactions = Transaction::with('user')->latest('transaction_date')->limit(5)->get();

        return view('dashboard', compact('totalProducts', 'totalTransactions', 'totalRevenue', 'lowStockProducts', 'recentTransactions'));

    }
}
