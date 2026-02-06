<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg sm:rounded-lg overflow-hidden">

                <div class="p-6 bg-gray-50 border-b text-center">
                    <h1 class="text-2xl font-bold text-gray-800 uppercase">Toko Komputer</h1>
                    <p class="text-sm text-gray-500">Bukti Pembayaran Sah</p>

                    <div class="mt-4 text-left text-sm text-gray-600 grid grid-cols-2 gap-2">
                        <div>
                            <span class="block text-gray-400 text-xs">NO. INVOICE</span>
                            <span class="font-mono font-bold text-gray-800">{{ $transaction->invoice_code }}</span>
                        </div>
                        <div class="text-right">
                            <span class="block text-gray-400 text-xs">TANGGAL</span>
                            <span class="font-bold">{{ $transaction->transaction_date->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>

                <div class="p-6">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-gray-400 border-b">
                                <th class="text-left py-2">Item</th>
                                <th class="text-center py-2">Qty</th>
                                <th class="text-right py-2">Total</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-700">
                            @foreach ($transaction->details as $item)
                                <tr class="border-b border-gray-100 last:border-0">
                                    <td class="py-3">
                                        <div class="font-bold">{{ $item->product->name }}</div>
                                        <div class="text-xs text-gray-500">@ Rp
                                            {{ number_format($item->price, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="text-center py-3">{{ $item->quantity }}</td>
                                    <td class="text-right py-3 font-medium">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 bg-gray-50 border-t space-y-2">
                    <div class="flex justify-between text-gray-600">
                        <span>Total Tagihan</span>
                        <span class="font-bold text-gray-900 text-lg">Rp
                            {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-500 text-sm">
                        <span>Tunai / Bayar</span>
                        <span>Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-500 text-sm">
                        <span>Kembalian</span>
                        <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="p-6 border-t bg-gray-100 flex justify-between items-center no-print">

                    <a href="{{ route('transactions.index') }}"
                        class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                        &larr; Kembali
                    </a>

                    <div class="flex space-x-3">
                        @if ($transaction->payment_amount < $transaction->total_amount)
                            <form action="{{ route('transactions.confirm', $transaction->id) }}" method="POST"
                                onsubmit="return confirm('Yakin pembayaran sudah diterima di rekening?');">
                                @csrf
                                <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Terima Pembayaran
                                </button>
                            </form>
                        @else
                            <span
                                class="bg-green-100 text-green-800 font-bold px-3 py-2 rounded border border-green-200 cursor-default">
                                ✅ SUDAH LUNAS
                            </span>
                        @endif

                        <button onclick="window.print()"
                            class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded shadow">
                            🖨️ Cetak Struk
                        </button>
                    </div>
                </div>

                <style>
                    @media print {

                        .no-print,
                        header,
                        nav {
                            display: none !important;
                        }

                        body {
                            background: white;
                        }
                    }
                </style>

            </div>
        </div>
    </div>
</x-app-layout>
