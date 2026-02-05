<x-app-layout>
    <x-slot name="header">
        {{ __('Kasir (Point of Sales)') }}
    </x-slot>

    <div class="py-12" x-data="posSystem({{ Js::from($products) }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 shadow-md" role="alert">
                    <p class="font-bold">Sukses!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 shadow-md" role="alert">
                    <p class="font-bold">Gagal!</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="flex flex-col md:flex-row gap-6">

                <div class="w-full md:w-2/3">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 h-full">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-800">Pilih Produk</h3>
                            <input type="text" x-model="search" placeholder="Cari produk..."
                                class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 overflow-y-auto"
                            style="max-height: 600px;">
                            <template x-for="product in filteredProducts" :key="product.id">
                                <div @click="addToCart(product)"
                                    class="border rounded-lg p-3 cursor-pointer hover:shadow-lg transition hover:border-indigo-500 flex flex-col items-center text-center bg-gray-50">

                                    <div class="h-20 w-20 mb-2 rounded bg-gray-200 overflow-hidden">
                                        <img x-show="product.image" :src="'/storage/' + product.image"
                                            class="h-full w-full object-cover">
                                        <div x-show="!product.image"
                                            class="h-full w-full flex items-center justify-center text-gray-400 text-xs font-bold">
                                            NO IMG</div>
                                    </div>

                                    <h4 class="text-sm font-semibold text-gray-900 leading-tight" x-text="product.name">
                                    </h4>
                                    <p class="text-xs text-gray-500 mt-1">Stok: <span x-text="product.stock"></span></p>
                                    <p class="text-sm font-bold text-indigo-600 mt-1"
                                        x-text="formatRupiah(product.price)"></p>
                                </div>
                            </template>

                            <div x-show="filteredProducts.length === 0"
                                class="col-span-full text-center py-8 text-gray-500">
                                Produk tidak ditemukan.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full md:w-1/3">
                    <div
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col h-full border-t-4 border-indigo-600">

                        <div class="p-4 border-b bg-gray-50">
                            <h3 class="text-lg font-bold text-gray-800">Keranjang Belanja</h3>
                        </div>

                        <div class="flex-1 p-4 overflow-y-auto bg-white relative"
                            style="max-height: 400px; min-height: 300px;">
                            <template x-if="cart.length === 0">
                                <div
                                    class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 space-y-2 opacity-50">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    <p class="text-sm">Keranjang Kosong</p>
                                </div>
                            </template>

                            <template x-for="(item, index) in cart" :key="item.id">
                                <div class="flex justify-between items-start mb-4 border-b pb-2 last:border-0">
                                    <div class="flex-1 pr-2">
                                        <h4 class="text-sm font-medium text-gray-900" x-text="item.name"></h4>
                                        <div class="text-xs text-gray-500"
                                            x-text="formatRupiah(item.price) + ' x ' + item.qty"></div>
                                    </div>
                                    <div class="flex flex-col items-end space-y-1">
                                        <span class="text-sm font-bold text-gray-800"
                                            x-text="formatRupiah(item.price * item.qty)"></span>

                                        <div class="flex items-center border rounded bg-gray-50">
                                            <button @click="updateQty(index, -1)"
                                                class="px-2 py-0.5 text-gray-600 hover:bg-gray-200 rounded-l">-</button>
                                            <span class="px-2 text-xs font-bold" x-text="item.qty"></span>
                                            <button @click="updateQty(index, 1)"
                                                class="px-2 py-0.5 text-gray-600 hover:bg-gray-200 rounded-r">+</button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="p-4 bg-gray-50 border-t">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Total Tagihan</span>
                                <span class="text-xl font-bold text-gray-900" x-text="formatRupiah(grandTotal)"></span>
                            </div>

                            <form action="{{ route('transactions.store') }}" method="POST"
                                @submit.prevent="submitForm($event)">
                                @csrf

                                <div class="mb-4" x-data="{
                                    displayPayment: '',
                                    formatPaymentInput(value) {
                                        // Hapus semua karakter non-digit
                                        let number = value.replace(/\D/g, '');
                                        paymentAmount = number ? parseInt(number) : 0;
                                
                                        // Format dengan titik sebagai pemisah ribuan
                                        if (number) {
                                            this.displayPayment = 'Rp ' + parseInt(number).toLocaleString('id-ID');
                                        } else {
                                            this.displayPayment = '';
                                        }
                                    }
                                }">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Uang Diterima
                                        (Rp)</label>
                                    <input type="text" x-model="displayPayment"
                                        @input="formatPaymentInput($event.target.value)"
                                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-right font-mono font-bold text-lg"
                                        placeholder="Rp 0" required>
                                    <input type="hidden" name="payment_amount" :value="paymentAmount">
                                    <p class="text-xs text-gray-500 mt-1">Contoh: Rp 50.000 atau Rp 100.000</p>
                                </div>

                                <div class="flex justify-between items-center mb-4 text-sm"
                                    :class="change < 0 ? 'text-red-500' : 'text-green-600'">
                                    <span>Kembalian:</span>
                                    <span class="font-bold" x-text="formatRupiah(change)"></span>
                                </div>

                                <template x-for="(item, index) in cart" :key="item.id">
                                    <div>
                                        <input type="hidden" :name="'cart[' + index + '][id]'" :value="item.id">
                                        <input type="hidden" :name="'cart[' + index + '][qty]'" :value="item.qty">
                                    </div>
                                </template>

                                <button type="submit" :disabled="cart.length === 0 || paymentAmount < grandTotal"
                                    class="w-full text-white font-bold py-3 px-4 rounded-lg shadow-lg transition duration-150 text-base"
                                    :class="cart.length === 0 || paymentAmount < grandTotal ?
                                        'bg-gray-500 cursor-not-allowed' :
                                        'bg-indigo-600 hover:bg-indigo-700 hover:shadow-xl transform hover:-translate-y-0.5'">
                                    <span x-show="cart.length === 0">KERANJANG KOSONG</span>
                                    <span x-show="cart.length > 0 && paymentAmount < grandTotal">UANG KURANG</span>
                                    <span x-show="cart.length > 0 && paymentAmount >= grandTotal">BAYAR SEKARANG</span>
                                </button>
                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function posSystem(productsData) {
            return {
                products: productsData,
                search: '',
                cart: [],
                paymentAmount: 0,

                // Filter Produk berdasarkan Search
                get filteredProducts() {
                    if (this.search === '') return this.products;
                    return this.products.filter(p => p.name.toLowerCase().includes(this.search.toLowerCase()));
                },

                // Tambah ke Keranjang
                addToCart(product) {
                    // Cek stok dulu
                    if (product.stock <= 0) {
                        alert('Stok Habis!');
                        return;
                    }

                    // Cek apakah produk sudah ada di cart
                    let existingItem = this.cart.find(item => item.id === product.id);

                    if (existingItem) {
                        if (existingItem.qty < product.stock) {
                            existingItem.qty++;
                        } else {
                            alert('Stok tidak cukup!');
                        }
                    } else {
                        // Tambah item baru
                        this.cart.push({
                            id: product.id,
                            name: product.name,
                            price: product.price,
                            qty: 1,
                            maxStock: product.stock
                        });
                    }
                },

                // Update Quantity (+ / -)
                updateQty(index, change) {
                    let item = this.cart[index];
                    let newQty = item.qty + change;

                    if (newQty > item.maxStock) {
                        alert('Stok mentok!');
                        return;
                    }

                    if (newQty <= 0) {
                        // Hapus item jika qty 0
                        this.cart.splice(index, 1);
                    } else {
                        item.qty = newQty;
                    }
                },

                // Hitung Grand Total
                get grandTotal() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                },

                // Hitung Kembalian
                get change() {
                    return this.paymentAmount - this.grandTotal;
                },

                // Format Rupiah
                formatRupiah(number) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(number);
                },

                // Submit Form
                submitForm(event) {
                    if (this.cart.length === 0) {
                        alert('Keranjang kosong!');
                        return;
                    }
                    if (this.paymentAmount < this.grandTotal) {
                        alert('Uang pembayaran kurang!');
                        return;
                    }
                    // Jika lolos, submit form HTML
                    event.target.submit();
                }
            }
        }
    </script>
</x-app-layout>
