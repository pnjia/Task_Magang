<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Transaksi (Selesai)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Bar Pencarian dan Tombol Filter --}}
                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm mb-6" x-data="{ filterOpen: false }">
                        <div class="flex flex-col md:flex-row gap-4 items-start md:items-center">
                            {{-- Input Pencarian Invoice dengan Ikon --}}
                            <div class="flex-1 max-w-md">
                                <form action="{{ route('transactions.history') }}" method="GET" class="relative">
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                        <input type="text" name="search" value="{{ request('search') }}"
                                            placeholder="Cari invoice..."
                                            class="w-full pl-10 pr-4 py-2 border-gray-300 rounded-lg text-sm focus:ring focus:ring-blue-200">
                                    </div>
                                    {{-- Hidden inputs untuk mempertahankan filter --}}
                                    <input type="hidden" name="date_from" value="{{ request('date_from') }}">
                                    <input type="hidden" name="date_to" value="{{ request('date_to') }}">
                                </form>
                            </div>

                            {{-- Tombol Toggle Filter --}}
                            <button type="button" @click="filterOpen = !filterOpen"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                    </path>
                                </svg>
                                Filter
                                <svg class="w-4 h-4 transition-transform" x-bind:class="filterOpen ? 'rotate-180' : ''"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </div>

                        {{-- Section Filter (Collapsible) --}}
                        <div x-show="filterOpen" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform -translate-y-2"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform translate-y-0"
                            x-transition:leave-end="opacity-0 transform -translate-y-2"
                            class="mt-4 pt-4 border-t border-gray-200" style="display: none;">
                            <form action="{{ route('transactions.history') }}" method="GET">
                                {{-- Pertahankan search query --}}
                                <input type="hidden" name="search" value="{{ request('search') }}">

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                                    {{-- Filter Tanggal Dari --}}
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Dari</label>
                                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                                            class="w-full border-gray-300 rounded text-sm focus:ring focus:ring-blue-200">
                                    </div>

                                    {{-- Filter Tanggal Sampai --}}
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Sampai</label>
                                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                                            class="w-full border-gray-300 rounded text-sm focus:ring focus:ring-blue-200">
                                    </div>

                                    {{-- Tombol Aksi --}}
                                    <div class="flex items-end gap-2">
                                        <button type="submit"
                                            class="bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-indigo-700 font-medium transition w-full md:w-auto">
                                            Terapkan Filter
                                        </button>
                                        <a href="{{ route('transactions.history') }}"
                                            class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-300 font-medium transition text-center">
                                            Reset
                                        </a>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">Tanggal</th>
                                    <th class="px-6 py-3">Invoice</th>
                                    <th class="px-6 py-3">Kasir/User</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Total</th>
                                    <th class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $trx)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            {{ $trx->transaction_date->format('d M Y') }}
                                        </td>
                                        <td class="px-6 py-4 font-bold text-gray-900">
                                            {{ $trx->invoice_code }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $trx->user->name ?? 'Guest' }}
                                        </td>

                                        {{-- BAGIAN MENAMPILKAN STATUS --}}
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                // Mapping Warna Badge
                                                $colors = [
                                                    'unpaid' => 'bg-red-100 text-red-800',
                                                    'paid' => 'bg-blue-100 text-blue-800',
                                                    'processing' => 'bg-yellow-100 text-yellow-800',
                                                    'shipped' => 'bg-purple-100 text-purple-800',
                                                    'completed' => 'bg-green-100 text-green-800',
                                                    'cancelled' => 'bg-gray-100 text-gray-800',
                                                ];

                                                // Mapping Teks Bahasa Indonesia
                                                $labels = [
                                                    'unpaid' => 'Belum Bayar',
                                                    'paid' => 'Sudah Dibayar',
                                                    'processing' => 'Sedang Diproses',
                                                    'shipped' => 'Sedang Dikirim',
                                                    'completed' => 'Selesai',
                                                    'cancelled' => 'Dibatalkan',
                                                ];
                                            @endphp

                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colors[$trx->status] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ $labels[$trx->status] ?? ucfirst($trx->status) }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 font-bold text-green-600">
                                            Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                        </td>

                                        {{-- BAGIAN AKSI --}}
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('transactions.show', $trx->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900 font-bold hover:underline">
                                                Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 italic">
                                            Belum ada transaksi.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
