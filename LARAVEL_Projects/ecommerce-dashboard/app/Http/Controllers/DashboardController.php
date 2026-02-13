<?php

namespace App\Http\Controllers;

use App\Exports\SalesExport;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $totalProducts = Product::count();
        $totalTransactions = Transaction::count();
        $totalRevenue = Transaction::sum('total_amount');

        $lowStockProducts = Product::with('category')->where('stock', '<', 10)->orderBy('stock', 'asc')->limit(5)->get();

        $recentTransactions = Transaction::with('user')->latest('transaction_date')->limit(5)->get()->map(function ($transaction) {
            return [
                'id' => $transaction->id,
                'invoice_code' => $transaction->invoice_code,
                'total_amount' => $transaction->total_amount,
                // include created_at so frontend can compute relative time from DB timestamp
                'created_at' => $transaction->created_at?->toDateTimeString(),
                'transaction_date_human' => $transaction->transaction_date->diffForHumans(),
            ];
        });

        if (request()->is('api/*') || request()->wantsJson()) {
            return response()->json([
                'totalProducts' => $totalProducts,
                'totalTransactions' => $totalTransactions,
                'totalRevenue' => $totalRevenue,
                'lowStockProducts' => $lowStockProducts,
                'recentTransactions' => $recentTransactions,
            ]);
        } else {
            return Inertia::render('Dashboard', [
                'totalProducts' => $totalProducts,
                'totalTransactions' => $totalTransactions,
                'totalRevenue' => $totalRevenue,
                'lowStockProducts' => $lowStockProducts,
                'recentTransactions' => $recentTransactions,
            ]);
        }
    }

    /**
     * Export laporan penjualan ke Excel.
     */
    public function exportSales()
    {
        $filename = 'laporan-penjualan-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new SalesExport, $filename);
    }
}
