<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pesanan Masuk (Aktif)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 shadow-sm rounded-r"
                    role="alert">
                    <p class="font-bold">Berhasil!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Bar Pencarian dan Tombol Filter --}}
                    <div class="bg-gray-50 p-4 rounded-lg shadow-sm mb-6" x-data="{ filterOpen: false }">
                        <div class="flex flex-col md:flex-row gap-4 items-start md:items-center">
                            {{-- Input Pencarian Invoice dengan Ikon --}}
                            <div class="flex-1 max-w-md">
                                <form action="{{ route('transactions.index') }}" method="GET" class="relative">
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
                                    <input type="hidden" name="filter_status" value="{{ request('filter_status') }}">
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
                            <form action="{{ route('transactions.index') }}" method="GET">
                                {{-- Pertahankan search query --}}
                                <input type="hidden" name="search" value="{{ request('search') }}">

                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

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

                                    {{-- Filter Status --}}
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                                        <select name="filter_status"
                                            class="w-full border-gray-300 rounded text-sm focus:ring focus:ring-blue-200">
                                            <option value="">Semua Status</option>
                                            <option value="unpaid"
                                                {{ request('filter_status') == 'unpaid' ? 'selected' : '' }}>Belum
                                                Bayar</option>
                                            <option value="paid"
                                                {{ request('filter_status') == 'paid' ? 'selected' : '' }}>Sudah Bayar
                                            </option>
                                            <option value="processing"
                                                {{ request('filter_status') == 'processing' ? 'selected' : '' }}>
                                                Diproses</option>
                                            <option value="shipped"
                                                {{ request('filter_status') == 'shipped' ? 'selected' : '' }}>Dikirim
                                            </option>
                                        </select>
                                    </div>

                                    {{-- Tombol Aksi --}}
                                    <div class="flex items-end gap-2">
                                        <button type="submit"
                                            class="bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-indigo-700 font-medium transition w-full md:w-auto">
                                            Terapkan Filter
                                        </button>
                                        <a href="{{ route('transactions.index') }}"
                                            class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-300 font-medium transition text-center">
                                            Reset
                                        </a>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                    @if ($transactions->isEmpty())
                        <div class="flex flex-col items-center justify-center py-12 text-gray-500">
                            <svg class="w-16 h-16 mb-4 text-gray-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                            <p class="text-lg">Belum ada pesanan aktif.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b">
                                    <tr>
                                        <th class="px-6 py-3">Tanggal</th>
                                        <th class="px-6 py-3">Invoice</th>
                                        <th class="px-6 py-3">Pembeli</th>
                                        <th class="px-6 py-3">Total</th>
                                        <th class="px-6 py-3 text-center">Update Status</th>
                                        <th class="px-6 py-3 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($transactions as $trx)
                                        <tr class="bg-white hover:bg-gray-50 transition-colors">

                                            {{-- Tanggal --}}
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ \Carbon\Carbon::parse($trx->transaction_date)->format('d M Y') }}
                                                <div class="text-xs text-gray-400">
                                                    {{ \Carbon\Carbon::parse($trx->created_at)->format('H:i') }}</div>
                                            </td>

                                            {{-- Invoice --}}
                                            <td class="px-6 py-4 font-bold text-gray-900">
                                                {{ $trx->invoice_code }}
                                            </td>

                                            {{-- Pembeli --}}
                                            <td class="px-6 py-4">
                                                <div class="font-medium text-gray-900">
                                                    {{ $trx->customer_name ?? 'Pelanggan Umum' }}</div>
                                                <div class="text-xs text-gray-500">{{ $trx->customer_phone ?? '-' }}
                                                </div>
                                            </td>

                                            {{-- Total --}}
                                            <td class="px-6 py-4 font-bold text-indigo-600">
                                                Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                            </td>

                                            {{-- DROPDOWN STATUS CANTIK --}}
                                            <td class="px-6 py-4 text-center">
                                                <form action="{{ route('transactions.update', $trx->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')

                                                    @php
                                                        // Tentukan Warna Border & Text Berdasarkan Status
                                                        $statusClass = match ($trx->status) {
                                                            'unpaid'
                                                                => 'border-red-300 text-red-700 bg-red-50 focus:ring-red-500',
                                                            'paid'
                                                                => 'border-blue-300 text-blue-700 bg-blue-50 focus:ring-blue-500',
                                                            'processing'
                                                                => 'border-yellow-300 text-yellow-700 bg-yellow-50 focus:ring-yellow-500',
                                                            'shipped'
                                                                => 'border-purple-300 text-purple-700 bg-purple-50 focus:ring-purple-500',
                                                            default => 'border-gray-300 text-gray-700 bg-white',
                                                        };
                                                    @endphp

                                                    <div class="relative inline-block w-40">
                                                        <select name="status" onchange="this.form.submit()"
                                                            class="appearance-none w-full text-xs font-bold py-2 pl-3 pr-8 rounded-lg border-2 cursor-pointer outline-none focus:ring-2 focus:ring-opacity-50 transition shadow-sm {{ $statusClass }}">

                                                            <option value="unpaid"
                                                                {{ $trx->status == 'unpaid' ? 'selected' : '' }}>🔴
                                                                Belum Bayar</option>
                                                            <option value="paid"
                                                                {{ $trx->status == 'paid' ? 'selected' : '' }}>🔵 Lunas
                                                                (Paid)
                                                            </option>
                                                            <option value="processing"
                                                                {{ $trx->status == 'processing' ? 'selected' : '' }}>🟡
                                                                Proses (Pack)</option>
                                                            <option value="shipped"
                                                                {{ $trx->status == 'shipped' ? 'selected' : '' }}>🟣
                                                                Kirim (Ship)</option>
                                                            <option disabled>──────────</option>
                                                            <option value="completed"
                                                                class="text-green-600 font-bold">✅
                                                                Selesai</option>
                                                            <option value="cancelled" class="text-gray-500">❌ Batalkan
                                                            </option>
                                                        </select>

                                                        <div
                                                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                                            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </form>
                                            </td>

                                            {{-- Aksi --}}
                                            <td class="px-6 py-4 text-right">
                                                <a href="{{ route('transactions.show', $trx->id) }}"
                                                    class="text-gray-400 hover:text-indigo-600 font-bold transition">
                                                    <svg class="w-5 h-5 inline-block" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                        </path>
                                                    </svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $transactions->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
