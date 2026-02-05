<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Bisnis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-gray-500 text-sm font-medium uppercase">Total Pendapatan</div>
                    <div class="text-2xl font-bold text-gray-800 mt-2">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500 text-sm font-medium uppercase">Total Transaksi</div>
                    <div class="text-2xl font-bold text-gray-800 mt-2">
                        {{ $totalTransactions }} <span class="text-sm font-normal text-gray-400">Nota</span>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                    <div class="text-gray-500 text-sm font-medium uppercase">Total Produk</div>
                    <div class="text-2xl font-bold text-gray-800 mt-2">
                        {{ $totalProducts }} <span class="text-sm font-normal text-gray-400">SKU</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                    <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h3 class="font-bold text-gray-700">⚠️ Stok Menipis (< 10)</h3>
                                <a href="{{ route('products.index') }}"
                                    class="text-xs text-indigo-600 hover:underline font-semibold">KELOLA</a>
                    </div>
                    <div class="p-4">
                        @if ($lowStockProducts->count() > 0)
                            <ul class="space-y-3">
                                @foreach ($lowStockProducts as $product)
                                    <li
                                        class="flex justify-between items-center bg-red-50 p-3 rounded border border-red-100">
                                        <div>
                                            <div class="font-bold text-gray-800 text-sm">{{ $product->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $product->category->name ?? '-' }}
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <span class="block text-xs text-gray-500">Sisa</span>
                                            <span class="text-red-600 font-bold text-lg">{{ $product->stock }}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                                <svg class="w-12 h-12 mb-2 text-green-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm">Stok aman terkendali.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                    <div class="p-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                        <h3 class="font-bold text-gray-700">Transaksi Terakhir</h3>
                        <a href="{{ route('transactions.index') }}"
                            class="text-xs text-indigo-600 hover:underline font-semibold">SEMUA</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b">
                                <tr>
                                    <th class="px-4 py-3">Invoice</th>
                                    <th class="px-4 py-3">Total</th>
                                    <th class="px-4 py-3 text-right">Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTransactions as $trx)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-4 py-3 font-medium text-gray-900">{{ $trx->invoice_code }}</td>
                                        <td class="px-4 py-3 font-bold text-green-600">Rp
                                            {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 text-right text-xs text-gray-500">
                                            {{ $trx->transaction_date->diffForHumans() }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-6 text-center italic text-gray-400">Belum ada
                                            penjualan hari ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
