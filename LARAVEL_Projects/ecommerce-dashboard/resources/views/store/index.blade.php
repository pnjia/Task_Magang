<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tenant->name }} - Toko Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800">

    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-4">
                    <a href="/" class="text-gray-400 hover:text-indigo-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div class="flex items-center gap-2">
                        <div class="bg-indigo-600 text-white p-2 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>
                        <h1 class="font-bold text-xl tracking-tight text-gray-900">{{ $tenant->name }}</h1>
                    </div>
                </div>

                <a href="{{ route('cart.global') }}"
                    class="relative group p-2 rounded-full hover:bg-gray-100 transition flex items-center gap-2">
                    <span
                        class="text-sm font-medium text-gray-600 group-hover:text-indigo-600 hidden md:block">Keranjang</span>
                    <div class="relative">
                        <svg class="h-7 w-7 text-gray-600 group-hover:text-indigo-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>

                        @php
                            $totalQty = 0;
                            if (session('cart')) {
                                foreach (session('cart') as $details) {
                                    $totalQty += $details['quantity'];
                                }
                            }
                        @endphp

                        @if ($totalQty > 0)
                            <span
                                class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-bold rounded-full h-5 w-5 flex items-center justify-center shadow-md border-2 border-white">
                                {{ $totalQty }}
                            </span>
                        @endif
                    </div>
                </a>
            </div>
        </div>
    </nav>

    <div class="bg-indigo-700 text-white py-12 mb-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-extrabold mb-2">Selamat Datang di {{ $tenant->name }}</h2>
            <p class="text-indigo-200">Temukan produk terbaik kami di sini.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-20">
        @if ($products->isEmpty())
            <div class="text-center py-20">
                <p class="text-gray-500 text-lg">Belum ada produk yang tersedia saat ini.</p>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($products as $product)
                    <div
                        class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-shadow duration-300 overflow-hidden border border-gray-100 flex flex-col h-full group">

                        <div class="relative h-48 bg-gray-200 overflow-hidden">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="flex items-center justify-center h-full text-gray-400">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                            @endif

                            <a href="{{ route('store.product', ['slug' => $tenant->slug, 'productSlug' => $product->slug]) }}"
                                class="absolute inset-0 z-10"></a>
                        </div>

                        <div class="p-4 flex-1 flex flex-col">
                            <a href="{{ route('store.product', ['slug' => $tenant->slug, 'productSlug' => $product->slug]) }}"
                                class="font-bold text-gray-800 text-lg mb-1 leading-tight hover:text-indigo-600 transition">
                                {{ $product->name }}
                            </a>

                            <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $product->description }}</p>

                            <div class="mt-auto flex items-center justify-between">
                                <span class="font-bold text-indigo-700 text-lg">Rp
                                    {{ number_format($product->price, 0, ',', '.') }}</span>

                                <button
                                    onclick="addToCart(event, '{{ route('cart.add', ['slug' => $tenant->slug, 'productId' => $product->id]) }}')"
                                    class="relative z-20 bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-full shadow-md transition transform active:scale-95 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        function addToCart(event, url) {
            event.preventDefault();
            let button = event.currentTarget;
            let originalContent = button.innerHTML;
            button.innerHTML =
                `<svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
            button.disabled = true;

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        alert('Produk berhasil masuk keranjang!'); // Bisa diganti Toast
                        location.reload(); // Reload agar badge keranjang update (simple way)
                    }
                })
                .catch(error => alert('Gagal menambahkan produk.'))
                .finally(() => {
                    button.innerHTML = originalContent;
                    button.disabled = false;
                });
        }
    </script>
</body>

</html>
