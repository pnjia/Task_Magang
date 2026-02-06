<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja Saya</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-50 text-gray-800">

    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 text-gray-500 hover:text-indigo-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="font-medium">Kembali Belanja</span>
            </a>
            <h1 class="text-xl font-bold text-gray-800">Keranjang Saya</h1>

            @php
                $globalQty = 0;
                if (session('cart')) {
                    $globalQty = array_sum(array_column(session('cart'), 'quantity'));
                }
            @endphp
            <div class="relative p-2">
                <svg class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span id="global-badge"
                    class="absolute top-0 right-0 bg-red-600 text-white text-[10px] font-bold rounded-full h-5 w-5 flex items-center justify-center {{ $globalQty > 0 ? '' : 'hidden' }}">
                    {{ $globalQty }}
                </span>
            </div>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto px-4 py-8">

        @if ($cartsByStore->isEmpty())
            <div id="empty-state" class="text-center py-20">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                <p class="text-gray-500 text-lg mb-6">Keranjang Anda masih kosong.</p>
                <a href="/"
                    class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 font-bold">Mulai
                    Belanja</a>
            </div>
        @else
            @foreach ($cartsByStore as $storeName => $items)
                <div id="store-container-{{ Str::slug($storeName) }}"
                    class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8 overflow-hidden">

                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <div
                                class="bg-indigo-100 text-indigo-600 w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm">
                                {{ substr($storeName, 0, 1) }}
                            </div>
                            <h2 class="font-bold text-lg">{{ $storeName }}</h2>
                        </div>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @php
                            $subtotal = 0;
                            $storePhone = '';
                        @endphp
                        @foreach ($items as $item)
                            @php
                                $lineTotal = $item['price'] * $item['quantity'];
                                $subtotal += $lineTotal;
                                // Ambil phone dari item, fallback ke default jika kosong
                                $storePhone = $item['tenant_phone'] ?? '6281234567890';
                                $realKey = $item['key'];
                            @endphp

                            <div id="item-row-{{ $realKey }}"
                                class="p-6 flex gap-4 items-center transition-all duration-300">
                                <div
                                    class="w-20 h-20 bg-gray-100 rounded-md overflow-hidden flex-shrink-0 border border-gray-200">
                                    @if ($item['image'])
                                        <img src="{{ asset('storage/' . $item['image']) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div class="flex items-center justify-center h-full text-gray-400 text-xs">No
                                            IMG</div>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <h3 class="font-bold text-gray-900 text-lg">{{ $item['name'] }}</h3>
                                    <div class="text-gray-500 text-sm mb-3">Rp
                                        {{ number_format($item['price'], 0, ',', '.') }} / pcs</div>

                                    <div class="flex items-center gap-3">
                                        <button onclick="updateCart('{{ $realKey }}', 'minus')"
                                            class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600 font-bold transition active:scale-90">-</button>
                                        <span id="qty-{{ $realKey }}"
                                            class="font-medium text-gray-900 w-8 text-center">{{ $item['quantity'] }}</span>
                                        <button onclick="updateCart('{{ $realKey }}', 'plus')"
                                            class="w-8 h-8 rounded-full bg-indigo-100 hover:bg-indigo-200 flex items-center justify-center text-indigo-700 font-bold transition active:scale-90">+</button>
                                    </div>
                                </div>

                                <div class="text-right flex flex-col justify-between h-20">
                                    <div class="font-bold text-lg text-indigo-700">Rp <span
                                            id="line-total-{{ $realKey }}">{{ number_format($lineTotal, 0, ',', '.') }}</span>
                                    </div>
                                    <button onclick="removeItem('{{ $realKey }}')"
                                        class="text-sm text-red-500 hover:text-red-700 flex items-center justify-end gap-1 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-between items-center">
                        <div>
                            <span class="text-sm text-gray-500">Subtotal Toko ini:</span>
                            <div class="text-xl font-bold text-indigo-700">Rp <span
                                    id="subtotal-{{ Str::slug($storeName) }}">{{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <button
                            onclick='openCheckoutModal(@json($items), "{{ $subtotal }}", "{{ $storePhone }}", "{{ $storeName }}")'
                            class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg font-bold flex items-center gap-2 transition shadow-sm">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                            </svg>
                            Isi Data & Checkout
                        </button>
                    </div>
                </div>
            @endforeach

        @endif
    </div>

    <div id="checkoutModal"
        class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm hidden flex items-center justify-center z-[100] transition-opacity duration-300">

        <div
            class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all scale-100">

            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-5 flex justify-between items-center">
                <div>
                    <h3 class="text-white font-bold text-xl tracking-wide">Data Pemesan</h3>
                    <p class="text-indigo-100 text-xs mt-1">Lengkapi data untuk proses pengiriman</p>
                </div>
                <button onclick="closeModal()"
                    class="text-white hover:text-gray-200 hover:bg-white/20 rounded-full p-1 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div class="p-8">
                <div class="space-y-6">
                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 pl-1">Nama Lengkap</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input type="text" id="buyer_name"
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200 placeholder-gray-400 text-gray-800"
                                placeholder="Contoh: Budi Santoso">
                        </div>
                    </div>

                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 pl-1">Nomor WhatsApp</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                    </path>
                                </svg>
                            </div>
                            <input type="number" id="buyer_phone"
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200 placeholder-gray-400 text-gray-800"
                                placeholder="08123xxxx">
                        </div>
                    </div>

                    <div class="group">
                        <label class="block text-sm font-semibold text-gray-700 mb-2 pl-1">Alamat Pengiriman</label>
                        <textarea id="buyer_address" rows="3"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200 placeholder-gray-400 text-gray-800 resize-none"
                            placeholder="Jln. Mawar No. 10, RT/RW..."></textarea>
                    </div>
                </div>

                <div class="mt-8 pt-2">
                    <button onclick="processCheckout()"
                        class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3.5 rounded-xl shadow-lg hover:shadow-green-500/30 transform hover:-translate-y-0.5 transition-all duration-200 flex justify-center items-center gap-2">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                        </svg>
                        <span class="tracking-wide">Kirim Pesanan ke WA</span>
                    </button>
                    <p class="text-center text-gray-400 text-xs mt-3">Pesanan akan diteruskan langsung ke WhatsApp
                        Penjual</p>
                </div>

            </div>
        </div>
    </div>

    <script>
        // --- VARIABLE GLOBAL ---
        let currentStoreItems = [];
        let currentStoreTotal = 0;
        let currentStorePhone = '';

        // --- 1. FUNGSI BUKA MODAL (Dipanggil tombol "Isi Data & Checkout") ---
        function openCheckoutModal(items, total, phone, storeName) {
            currentStoreItems = items;
            currentStoreTotal = total;
            currentStorePhone = phone;

            // Hapus class hidden agar modal muncul
            let modal = document.getElementById('checkoutModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex'); // Pastikan display flex agar tengah
            } else {
                console.error("Modal tidak ditemukan! Pastikan ID 'checkoutModal' ada di HTML.");
            }
        }

        // --- 2. FUNGSI TUTUP MODAL ---
        function closeModal() {
            let modal = document.getElementById('checkoutModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        // --- 3. FUNGSI PROSES CHECKOUT (Fix Pop-up Blocker & Database) ---
        function processCheckout() {
            let name = document.getElementById('buyer_name').value;
            let phone = document.getElementById('buyer_phone').value;
            let address = document.getElementById('buyer_address').value;

            if (!name || !address || !phone) {
                alert('Mohon lengkapi Nama, Nomor HP, dan Alamat!');
                return;
            }

            // A. BUKA TAB BARU SEKARANG (Agar tidak diblokir browser)
            let waWindow = window.open('', '_blank');
            if (waWindow) {
                waWindow.document.write(`
                    <html>
                        <head><title>Memproses...</title></head>
                        <body style="display:flex;justify-content:center;align-items:center;height:100vh;font-family:sans-serif;background:#f3f4f6;">
                            <div style="text-align:center;">
                                <h2 style="color:#4f46e5;">Sedang Memproses Pesanan...</h2>
                                <p>Mohon jangan tutup halaman ini.</p>
                                <p>Anda akan dialihkan ke WhatsApp sebentar lagi...</p>
                            </div>
                        </body>
                    </html>
                `);
            } else {
                alert("Pop-up diblokir! Mohon izinkan pop-up untuk situs ini.");
                return;
            }

            // B. UPDATE TAMPILAN TOMBOL (Loading)
            let btnSubmit = document.querySelector('#checkoutModal button[onclick="processCheckout()"]');
            let originalText = btnSubmit.innerHTML;
            btnSubmit.innerHTML = 'Menyimpan...';
            btnSubmit.disabled = true;

            // Data untuk dikirim ke Laravel
            let checkoutData = {
                tenant_slug: currentStoreItems[0].tenant_slug,
                customer_name: name,
                customer_phone: phone,
                customer_address: address,
                invoice_code: 'INV-' + Math.random().toString(36).substr(2, 9).toUpperCase(),
                total_price: currentStoreTotal,
                items: currentStoreItems
            };

            // C. KIRIM KE SERVER (AJAX)
            fetch("{{ route('checkout.process') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(checkoutData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // D. JIKA SUKSES, Redirect Tab tadi ke WhatsApp
                        let message = `Halo kak, saya mau pesan:\n\n`;
                        message += `No Invoice: ${checkoutData.invoice_code}\n`;
                        message += `Nama: ${name}\n`;
                        message += `No HP: ${phone}\n`;
                        message += `Alamat: ${address}\n`;
                        message += `Pesanan:\n`;

                        currentStoreItems.forEach(item => {
                            message += `- ${item.name} (x${item.quantity})\n`;
                        });

                        // Format Rupiah Manual (Fallback jika Intl error)
                        let formattedTotal = "Rp " + new Intl.NumberFormat('id-ID').format(currentStoreTotal);
                        message += `\nTotal: ${formattedTotal}\n\n`;
                        message += `Mohon info nomor rekening untuk pembayaran. Terima kasih`;

                        let waUrl = `https://wa.me/${currentStorePhone}?text=${encodeURIComponent(message)}`;

                        // Redirect Tab Loading ke WA
                        if (waWindow) waWindow.location.href = waUrl;

                        // Reload Halaman Utama
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);

                    } else {
                        if (waWindow) waWindow.close();
                        alert('Gagal: ' + data.message);
                        btnSubmit.innerHTML = originalText;
                        btnSubmit.disabled = false;
                    }
                })
                .catch(error => {
                    if (waWindow) waWindow.close();
                    console.error('Error:', error);
                    alert('Terjadi kesalahan sistem. Cek Console.');
                    btnSubmit.innerHTML = originalText;
                    btnSubmit.disabled = false;
                });
        }

        // --- 4. FUNGSI UPDATE KERANJANG (+/-) ---
        function updateCart(key, operation) {
            fetch(`/cart/change/${key}/${operation}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') handleSuccess(data);
                })
                .catch(err => console.error(err));
        }

        // --- 5. FUNGSI HAPUS ITEM ---
        function removeItem(key) {
            if (!confirm('Yakin ingin menghapus item ini?')) return;
            fetch(`/cart/remove/${key}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') handleSuccess(data);
                })
                .catch(err => console.error(err));
        }

        // --- 6. FUNGSI UPDATE TAMPILAN SETELAH AJAX ---
        function handleSuccess(data) {
            let badge = document.getElementById('global-badge');
            if (badge) {
                badge.innerText = data.new_global_qty;
                if (data.new_global_qty > 0) badge.classList.remove('hidden');
                else badge.classList.add('hidden');
            }

            if (data.action === 'remove') {
                let row = document.getElementById(`item-row-${data.key}`);
                if (row) row.remove();
            } else {
                let qtyEl = document.getElementById(`qty-${data.key}`);
                let totalEl = document.getElementById(`line-total-${data.key}`);
                if (qtyEl) qtyEl.innerText = data.new_qty;
                if (totalEl) totalEl.innerText = data.new_line_total;
            }

            if (data.store_slug) {
                let subtotalEl = document.getElementById(`subtotal-${data.store_slug}`);
                if (subtotalEl) subtotalEl.innerText = data.new_store_subtotal;
            }

            if (data.new_store_subtotal === "0" || data.new_global_qty === 0) {
                location.reload();
            }
        }
    </script>

</body>

</html>
