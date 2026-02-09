<x-app-layout>
    <x-slot name="header">
        {{ __('Produk Saya') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Daftar Produk</h3>
                        <a href="{{ route('products.create') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-md text-sm">
                            + Tambah Produk
                        </a>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto border rounded-lg">
                        {{-- Bar Pencarian dan Tombol Filter --}}
                        <div class="bg-white p-4 rounded shadow-sm mb-4" x-data="{ filterOpen: false }">
                            <div class="flex flex-col md:flex-row gap-4 items-start md:items-center">
                                {{-- Input Pencarian dengan Ikon --}}
                                <div class="flex-1 max-w-md">
                                    <form action="{{ route('products.index') }}" method="GET" class="relative">
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </div>
                                            <input type="text" name="search" value="{{ request('search') }}"
                                                placeholder="Cari nama produk..."
                                                class="w-full pl-10 pr-4 py-2 border-gray-300 rounded-lg text-sm focus:ring focus:ring-blue-200">
                                        </div>
                                        {{-- Hidden inputs untuk mempertahankan filter --}}
                                        <input type="hidden" name="filter_price" value="{{ request('filter_price') }}">
                                        <input type="hidden" name="filter_category"
                                            value="{{ request('filter_category') }}">
                                        <input type="hidden" name="filter_stock" value="{{ request('filter_stock') }}">
                                    </form>
                                </div>

                                {{-- Tombol Toggle Filter --}}
                                <button type="button" @click="filterOpen = !filterOpen"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                        </path>
                                    </svg>
                                    Filter
                                    <svg class="w-4 h-4" x-bind:class="filterOpen ? 'rotate-180' : ''" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </div>

                            {{-- Section Filter (Collapsible) --}}
                            <div x-show="filterOpen" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 transform translate-y-0"
                                x-transition:leave-end="opacity-0 transform -translate-y-2"
                                class="mt-4 pt-4 border-t border-gray-200" style="display: none;">
                                <form action="{{ route('products.index') }}" method="GET">
                                    {{-- Pertahankan search query --}}
                                    <input type="hidden" name="search" value="{{ request('search') }}">

                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Maksimal
                                                Harga</label>
                                            <select name="filter_price"
                                                class="w-full border-gray-300 rounded text-sm focus:ring focus:ring-blue-200">
                                                <option value="">Semua Harga</option>
                                                <option value="50000"
                                                    {{ request('filter_price') == '50000' ? 'selected' : '' }}>Rp
                                                    50.000 ke
                                                    bawah</option>
                                                <option value="100000"
                                                    {{ request('filter_price') == '100000' ? 'selected' : '' }}>Rp
                                                    100.000
                                                    ke bawah</option>
                                                <option value="500000"
                                                    {{ request('filter_price') == '500000' ? 'selected' : '' }}>Rp
                                                    500.000
                                                    ke bawah</option>
                                                <option value="1000000"
                                                    {{ request('filter_price') == '1000000' ? 'selected' : '' }}>Rp
                                                    1.000.000 ke bawah</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Kategori</label>
                                            <select name="filter_category"
                                                class="w-full border-gray-300 rounded text-sm focus:ring focus:ring-blue-200">
                                                <option value="">Semua Kategori</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ request('filter_category') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-1">Stok
                                                Menipis</label>
                                            <select name="filter_stock"
                                                class="w-full border-gray-300 rounded text-sm focus:ring focus:ring-blue-200">
                                                <option value="">Semua Stok</option>
                                                <option value="20"
                                                    {{ request('filter_stock') == '20' ? 'selected' : '' }}>Kurang dari
                                                    20
                                                </option>
                                                <option value="50"
                                                    {{ request('filter_stock') == '50' ? 'selected' : '' }}>Kurang dari
                                                    50
                                                </option>
                                                <option value="100"
                                                    {{ request('filter_stock') == '100' ? 'selected' : '' }}>Kurang
                                                    dari
                                                    100</option>
                                            </select>
                                        </div>

                                        <div class="flex items-end gap-2">
                                            <button type="submit"
                                                class="bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-indigo-700 font-medium transition w-full md:w-auto">
                                                Terapkan Filter
                                            </button>
                                            <a href="{{ route('products.index') }}"
                                                class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-300 font-medium transition text-center">
                                                Reset
                                            </a>
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                        <table class="w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                        style="width: 40%;">
                                        Produk
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                        style="width: 15%;">
                                        Kategori
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                        style="width: 15%;">
                                        Harga
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase"
                                        style="width: 15%;">
                                        Stok
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase"
                                        style="width: 15%;">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($products as $product)
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="flex items-start gap-4 max-w-full">
                                                <div class="h-16 w-16 flex-shrink-0">
                                                    @if ($product->image)
                                                        <img class="h-16 w-16 rounded-lg object-cover border border-gray-200 shadow-sm"
                                                            src="{{ Storage::url($product->image) }}"
                                                            alt="{{ $product->name }}">
                                                    @else
                                                        <div
                                                            class="h-16 w-16 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold border border-indigo-200 text-xl">
                                                            {{ substr($product->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div
                                                    class="min-w-0 flex-1 flex flex-col justify-center overflow-hidden">
                                                    <div
                                                        class="text-sm font-medium text-gray-900 break-words mb-2 pr-2">
                                                        {{ $product->name }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        <span
                                                            class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                            {{ $product->is_active ? 'Aktif' : 'Draft' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $product->category->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $product->stock }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('products.edit', $product->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900 mr-3 font-semibold">
                                                Edit
                                            </a>

                                            @can('delete-product')
                                                <form action="{{ route('products.destroy', $product->id) }}"
                                                    method="POST" class="inline-block"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini? Data yang dihapus tidak bisa dikembalikan.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 font-semibold">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            class="px-6 py-4 text-center text-gray-500 italic bg-gray-50">
                                            Belum ada produk. Yuk mulai jualan!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
