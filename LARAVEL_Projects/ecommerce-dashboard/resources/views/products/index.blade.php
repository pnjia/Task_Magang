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

                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini? Data yang dihapus tidak bisa dikembalikan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900 font-semibold">
                                                    Hapus
                                                </button>
                                            </form>
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
