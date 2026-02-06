<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

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

                                        <td class="px-6 py-4">
                                            @if ($trx->payment_amount >= $trx->total_amount)
                                                <span
                                                    class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded">LUNAS</span>
                                            @else
                                                <span
                                                    class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-1 rounded">MENUNGGU</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 font-bold text-green-600">
                                            Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                        </td>

                                        <td class="px-6 py-4 text-right text-sm font-medium">
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
