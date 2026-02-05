<x-app-layout>
    <x-slot name="header">
        {{ __('Edit Produk') }}
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Informasi Produk</h3>

                    <form action="{{ route('products.update', $product->id) }}" method="POST"
                        enctype="multipart/form-data" x-data="{
                            name: '{{ $product->name }}',
                            slug: '{{ $product->slug }}',
                            // PRE-FILL Image Preview jika ada gambar lama
                            preview: '{{ $product->image ? Storage::url($product->image) : null }}'
                        }">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                                    <input type="text" name="name" x-model="name"
                                        @input="slug = name.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '')"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm py-2"
                                        required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Slug (URL)</label>
                                    <input type="text" name="slug" x-model="slug"
                                        class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm text-sm py-2"
                                        required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                                    <select name="category_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm py-2"
                                        required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                    <textarea name="description" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">{{ $product->description }}</textarea>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div x-data="{
                                    displayPrice: 'Rp {{ number_format($product->price, 0, ',', '.') }}',
                                    actualPrice: {{ $product->price }},
                                    formatRupiah(value) {
                                        // Hapus semua karakter non-digit
                                        let number = value.replace(/\D/g, '');
                                        this.actualPrice = number;
                                
                                        // Format dengan titik sebagai pemisah ribuan
                                        if (number) {
                                            this.displayPrice = 'Rp ' + parseInt(number).toLocaleString('id-ID');
                                        } else {
                                            this.displayPrice = '';
                                        }
                                    }
                                }" x-init="formatRupiah(displayPrice)">
                                    <label class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                                    <div class="relative">
                                        <input type="text" x-model="displayPrice"
                                            @input="formatRupiah($event.target.value)" placeholder="Rp 0"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm py-2">
                                    </div>
                                    <input type="hidden" name="price" x-model="actualPrice" required>
                                    <p class="text-xs text-gray-500 mt-1">Contoh: Rp 10.000 atau Rp 1.000.000</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Stok</label>
                                    <input type="number" name="stock" min="0" value="{{ $product->stock }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm py-2"
                                        required>
                                </div>

                                <div x-data="{
                                    dragging: false,
                                    handleFile(files) {
                                        if (files.length > 0) {
                                            const file = files[0];
                                            if (file.type.startsWith('image/')) {
                                                // Update preview dengan gambar BARU yang dipilih user
                                                preview = URL.createObjectURL(file);
                                            }
                                        }
                                    }
                                }">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Produk</label>

                                    <div class="relative border-2 border-dashed rounded-lg p-4 transition-colors duration-200 ease-in-out text-center"
                                        :class="dragging ? 'border-blue-500 bg-blue-50' : 'border-gray-300 hover:bg-gray-50'"
                                        @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false"
                                        @drop.prevent="dragging = false; handleFile($event.dataTransfer.files); $refs.fileInput.files = $event.dataTransfer.files">

                                        <input type="file" name="image" id="image"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                            x-ref="fileInput" @change="handleFile($event.target.files)">

                                        <div x-show="!preview" class="space-y-1">
                                            <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor"
                                                fill="none" viewBox="0 0 48 48">
                                                <path
                                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="text-sm text-gray-600">
                                                <span class="font-medium text-blue-600 hover:text-blue-500">Ganti
                                                    Foto</span>
                                            </div>
                                            <p class="text-xs text-gray-500">Biarkan kosong jika tidak ingin mengubah
                                            </p>
                                        </div>

                                        <div x-show="preview" class="relative">
                                            <img :src="preview"
                                                class="mx-auto h-32 object-contain rounded-md shadow-sm">
                                            <p class="mt-2 text-xs text-gray-500">Klik atau drag gambar baru untuk
                                                mengubah</p>
                                        </div>

                                    </div>
                                    @error('image')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 border-t pt-4">
                            <a href="{{ route('products.index') }}"
                                class="text-gray-600 hover:text-gray-900 mr-6 text-sm">Batal</a>
                            <button type="submit"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-5 rounded-md shadow-lg transition transform hover:-translate-y-0.5 text-sm">
                                Update Produk
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
