<x-app-layout>
    <x-slot name="header">
        {{ __('Tambah Produk Baru') }}
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Produk</h3>

                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
                        x-data="{ name: '', slug: '' }">
                        @csrf

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
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                    <textarea name="description" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm"></textarea>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div x-data="{
                                    displayPrice: '',
                                    actualPrice: 0,
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
                                }">
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
                                    <label class="block text-sm font-medium text-gray-700">Stok Awal</label>
                                    <input type="number" name="stock" min="0" value="0"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm py-2"
                                        required>
                                </div>

                                <di v x-data="{
                                    preview: null,
                                    dragging: false,
                                    handleFile(files) {
                                        if (files.length > 0) {
                                            const file = files[0];
                                            // Validasi tipe file (hanya gambar)
                                            if (file.type.startsWith('image/')) {
                                                // Buat URL preview sementara dari browser
                                                this.preview = URL.createObjectURL(file);
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
                                                <span class="font-medium text-blue-600 hover:text-blue-500">Upload
                                                    file</span>
                                                <span class="pl-1">atau drag & drop disini</span>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 2MB</p>
                                        </div>

                                        <div x-show="preview" class="relative" style="display: none;">
                                            <img :src="preview"
                                                class="mx-auto h-32 object-contain rounded-md shadow-sm">
                                            <p class="mt-2 text-sm text-blue-600 font-medium cursor-pointer">Ganti
                                                Gambar</p>
                                        </div>

                                    </div>
                                    @error('image')
                                        <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </di>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 border-t pt-4">
                            <a href="{{ route('products.index') }}"
                                class="text-gray-600 hover:text-gray-900 mr-6 text-sm">Batal</a>
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-5 rounded-md shadow-lg transition transform hover:-translate-y-0.5 text-sm">
                                Simpan Produk
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
