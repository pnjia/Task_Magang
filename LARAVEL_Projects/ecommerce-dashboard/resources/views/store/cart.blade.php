<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keranjang - {{ $tenant->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>

<body class="bg-gray-50 font-sans antialiased">

    <div class="bg-white shadow-sm p-4 sticky top-0 z-10">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <a href="{{ route('store.index', $tenant->slug) }}" class="text-indigo-600 font-bold flex items-center">
                &larr; Lanjut Belanja
            </a>
            <h1 class="text-lg font-bold text-gray-800">Keranjang Belanja</h1>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-8">

        @if (session('cart') && count(session('cart')) > 0)
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-500 text-sm uppercase">
                        <tr>
                            <th class="px-6 py-3">Produk</th>
                            <th class="px-6 py-3 text-center">Harga</th>
                            <th class="px-6 py-3 text-center">Qty</th>
                            <th class="px-6 py-3 text-right">Subtotal</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php $total = 0; @endphp
                        @foreach (session('cart') as $id => $details)
                            @php
                                $subtotal = $details['price'] * $details['quantity'];
                                $total += $subtotal;
                            @endphp
                            <tr>
                                <td class="px-6 py-4 flex items-center">
                                    <div class="h-12 w-12 flex-shrink-0 overflow-hidden rounded border bg-gray-100">
                                        @if ($details['image'])
                                            <img src="{{ asset('storage/' . $details['image']) }}"
                                                class="h-full w-full object-cover">
                                        @else
                                            <span
                                                class="flex h-full w-full items-center justify-center text-xs text-gray-400">IMG</span>
                                        @endif
                                    </div>
                                    <div class="ml-4 font-medium text-gray-900">{{ $details['name'] }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">Rp {{ number_format($details['price'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">{{ $details['quantity'] }}</td>
                                <td class="px-6 py-4 text-right font-bold text-gray-800">Rp
                                    {{ number_format($subtotal, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('cart.remove', $tenant->slug) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $id }}">
                                        <button type="submit"
                                            class="text-red-500 hover:text-red-700 text-sm font-bold">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-end">
                <div class="bg-white p-6 rounded-lg shadow w-full md:w-1/2">
                    <div class="flex justify-between items-center mb-4 text-lg font-bold">
                        <span>Total Bayar</span>
                        <span class="text-indigo-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('checkout.index', $tenant->slug) }}"
                        class="block w-full text-center bg-indigo-600 text-white py-3 rounded-lg font-bold hover:bg-indigo-700 transition shadow-lg border-2 border-indigo-600">
                        Lanjut ke Pembayaran
                    </a>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                <p class="text-gray-500 text-lg mb-6">Keranjang Anda masih kosong.</p>
                <a href="{{ route('store.index', $tenant->slug) }}"
                    class="bg-indigo-600 text-white px-6 py-2 rounded-full font-bold hover:bg-indigo-700">
                    Mulai Belanja
                </a>
            </div>
        @endif

    </div>
</body>

</html>
