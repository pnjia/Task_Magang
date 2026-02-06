<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 font-sans text-gray-800">

    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('store.index', $tenant->slug) }}"
                    class="flex items-center gap-2 hover:opacity-80 transition">
                    <div class="bg-indigo-600 text-white p-2 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <span class="font-bold text-lg text-gray-900">{{ $tenant->name }}</span>
                </a>

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
                                $totalQty = array_sum(array_column(session('cart'), 'quantity'));
                            }
                        @endphp

                        <span id="cart-badge"
                            class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-bold rounded-full h-5 w-5 flex items-center justify-center shadow-md border-2 border-white {{ $totalQty > 0 ? '' : 'hidden' }}">
                            {{ $totalQty }}
                        </span>
                    </div>
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="text-sm text-gray-500 mb-6">
            <a href="/" class="hover:underline">Marketplace</a> /
            <a href="{{ route('store.index', $tenant->slug) }}" class="hover:underline">{{ $tenant->name }}</a> /
            <span class="text-gray-900">{{ $product->name }}</span>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="grid grid-cols-1 md:grid-cols-2">

                <div class="bg-gray-100 h-96 md:h-auto flex items-center justify-center p-8">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                            class="max-h-full max-w-full rounded-lg shadow-lg">
                    @else
                        <svg class="w-32 h-32 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    @endif
                </div>

                <div class="p-8 md:p-12 flex flex-col justify-center">
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $product->name }}</h1>
                    <div class="text-2xl font-bold text-indigo-600 mb-6">Rp
                        {{ number_format($product->price, 0, ',', '.') }}</div>

                    <div class="prose prose-sm text-gray-500 mb-8">
                        <p>{{ $product->description ?? 'Tidak ada deskripsi produk.' }}</p>
                    </div>

                    <div class="flex items-center gap-4 mt-auto">
                        <div class="text-sm text-gray-500">
                            Stok: <span class="font-bold text-gray-800">{{ $product->stock }}</span>
                        </div>

                        <button
                            onclick="addToCart(event, '{{ route('cart.add', ['slug' => $tenant->slug, 'productId' => $product->id]) }}')"
                            class="flex-1 bg-indigo-600 text-white px-8 py-4 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex justify-center items-center">
                            + Masukkan Keranjang
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function addToCart(event, url) {
            event.preventDefault();

            let button = event.currentTarget;
            let originalContent = button.innerHTML;

            // Animasi Loading
            button.innerHTML =
                `<svg class="animate-spin h-5 w-5 text-white" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
            button.disabled = true;

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json' // Pastikan server tahu kita minta JSON
                    }
                })
                .then(response => response.json()) // Parsing JSON dari Controller
                .then(data => {
                    if (data.status === 'success') {
                        // 1. UPDATE BADGE MERAH
                        let badge = document.getElementById('cart-badge');
                        if (badge) {
                            badge.innerText = data.total_quantity; // Update Angka
                            badge.classList.remove('hidden'); // Munculkan jika tersembunyi
                        }

                        // 2. Feedback Tombol
                        button.innerHTML = "Berhasil Masuk!";
                        button.classList.remove('bg-indigo-600');
                        button.classList.add('bg-green-600');

                        setTimeout(() => {
                            button.innerHTML = originalContent;
                            button.classList.remove('bg-green-600');
                            button.classList.add('bg-indigo-600');
                            button.disabled = false;
                        }, 1500);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal menambahkan produk.');
                    button.innerHTML = originalContent;
                    button.disabled = false;
                });
        }
    </script>

</body>

</html>
