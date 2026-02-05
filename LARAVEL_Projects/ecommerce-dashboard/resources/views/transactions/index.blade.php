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
                                    <th class="px-6 py-3">Kasir</th>
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
                                            {{ $trx->user->name ?? 'Hapus' }}
                                        </td>
                                        <td class="px-6 py-4 font-bold text-green-600">
                                            Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('transactions.show', $trx->id) }}"
                                                class="font-medium text-blue-600 hover:underline">
                                                Lihat Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-400 italic">
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
