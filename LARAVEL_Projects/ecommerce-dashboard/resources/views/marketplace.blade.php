<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marketplace Kita - Belanja Apa Saja</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans text-gray-800">

    <nav class="bg-white shadow sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="/" class="text-2xl font-bold text-indigo-600 flex items-center gap-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
                Marketplace
            </a>

            <form action="{{ route('home') }}" method="GET" class="hidden md:flex flex-1 mx-10">
                <input type="text" name="search" placeholder="Cari barang di semua toko..."
                    value="{{ request('search') }}"
                    class="w-full border-gray-300 rounded-l-lg focus:ring-indigo-500 focus:border-indigo-500">
                <button type="submit"
                    class="bg-indigo-600 text-white px-4 rounded-r-lg hover:bg-indigo-700">Cari</button>
            </form>

            <div class="flex items-center gap-4">
                <div class="flex items-center gap-4">

                    <a href="{{ route('cart.global') }}"
                        class="relative group p-2 rounded-full hover:bg-gray-100 transition mr-2">
                        <svg class="h-6 w-6 text-gray-600 group-hover:text-indigo-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>

                        @php
                            $totalQty = 0;
                            if (session('cart')) {
                                $totalQty = array_sum(array_column(session('cart'), 'quantity'));
                            }
                        @endphp

                        @if ($totalQty > 0)
                            <span
                                class="absolute top-0 right-0 bg-red-600 text-white text-[10px] font-bold rounded-full h-5 w-5 flex items-center justify-center shadow-md border-2 border-white">
                                {{ $totalQty }}
                            </span>
                        @endif
                    </a>
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-indigo-600 font-medium">Dashboard
                            Toko</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600">Masuk</a>
                        <a href="{{ route('register') }}"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Buka Toko</a>
                    @endauth
                </div>
            </div>
    </nav>

    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-extrabold mb-4">Temukan Jutaan Produk</h1>
            <p class="text-lg text-indigo-100">Dari berbagai penjual terpercaya di seluruh Indonesia</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Rekomendasi Terbaru</h2>

        @if ($products->isEmpty())
            <div class="text-center py-20 bg-white rounded-lg shadow">
                <p class="text-gray-500">Belum ada produk yang tersedia di marketplace ini.</p>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @foreach ($products as $product)
                    <div
                        class="bg-white rounded-lg shadow-sm hover:shadow-xl transition duration-300 border border-gray-100 overflow-hidden flex flex-col">

                        <div class="relative h-48 bg-gray-200 group">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full text-gray-400 bg-gray-100">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif

                            <a href="{{ route('store.product', ['slug' => $product->tenant->slug, 'productSlug' => $product->slug]) }}"
                                class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition"></a>
                        </div>

                        <div class="p-3 flex-1 flex flex-col">

                            <a href="{{ route('store.product', ['slug' => $product->tenant->slug, 'productSlug' => $product->slug]) }}"
                                class="font-semibold text-gray-800 text-sm mb-1 line-clamp-2 hover:text-indigo-600">
                                {{ $product->name }}
                            </a>

                            <div class="font-bold text-gray-900 mb-2">Rp
                                {{ number_format($product->price, 0, ',', '.') }}</div>

                            <div class="mt-auto pt-2 border-t border-gray-100 flex items-center gap-2">
                                <div
                                    class="w-5 h-5 bg-indigo-100 rounded-full flex items-center justify-center text-xs font-bold text-indigo-600">
                                    {{ substr($product->tenant->name, 0, 1) }}
                                </div>

                                <a href="{{ route('store.index', $product->tenant->slug) }}"
                                    class="text-xs text-gray-500 hover:text-indigo-600 truncate">
                                    {{ $product->tenant->name }}
                                </a>

                                <svg class="w-3 h-3 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</body>

</html>
