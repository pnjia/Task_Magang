<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout - {{ $tenant->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>

<body class="bg-gray-50 font-sans antialiased">

    <div class="max-w-3xl mx-auto px-4 py-12">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Konfirmasi Pesanan</h1>

        <div class="bg-white shadow-md rounded-lg overflow-hidden grid grid-cols-1 md:grid-cols-2">

            <div class="p-6 bg-gray-50 border-r">
                <h3 class="font-bold text-gray-700 mb-4">Ringkasan Belanja</h3>
                <ul class="space-y-3 mb-6">
                    @php $total = 0; @endphp
                    @foreach ($cart as $details)
                        @php $total += $details['price'] * $details['quantity']; @endphp
                        <li class="flex justify-between text-sm">
                            <span>{{ $details['name'] }} <span
                                    class="text-gray-500">x{{ $details['quantity'] }}</span></span>
                            <span class="font-semibold">Rp
                                {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</span>
                        </li>
                    @endforeach
                </ul>
                <div class="border-t pt-4 flex justify-between font-bold text-lg text-indigo-700">
                    <span>Total</span>
                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="p-6">
                <h3 class="font-bold text-gray-700 mb-4">Data Pengiriman</h3>

                @if (session('error'))
                    <div class="bg-red-100 text-red-600 p-2 text-sm rounded mb-4">{{ session('error') }}</div>
                @endif

                <form action="{{ route('checkout.process', $tenant->slug) }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Penerima</label>
                        <input type="text" name="name" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Contoh: Budi Santoso">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nomor WhatsApp</label>
                        <input type="number" name="phone" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="08123456789">
                        <p class="text-xs text-gray-500 mt-1">*Digunakan untuk konfirmasi pesanan.</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Alamat Lengkap</label>
                        <textarea name="address" rows="3" required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Nama Jalan, No Rumah, Kecamatan..."></textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-indigo-600 text-white font-bold py-3 rounded-md hover:bg-indigo-700 transition shadow-lg flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                        </svg>
                        Pesan Sekarang via WhatsApp
                    </button>

                    <a href="{{ route('cart.index', $tenant->slug) }}"
                        class="block text-center mt-4 text-sm text-gray-500 hover:text-indigo-600">Batal / Kembali ke
                        Keranjang</a>
                </form>
            </div>

        </div>
    </div>
</body>

</html>
