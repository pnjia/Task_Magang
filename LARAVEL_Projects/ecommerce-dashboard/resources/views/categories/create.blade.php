<x-app-layout>
    <x-slot name="header">
        {{ __('Tambah Kategori') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Buat Kategori Baru</h3>
                        <p class="text-sm text-gray-600">Isi detail kategori di bawah ini.</p>
                    </div>

                    <form action="{{ route('categories.store') }}" method="POST" x-data="{ name: '', slug: '' }">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   x-model="name"
                                   @input="slug = name.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '')"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   required
                                   autofocus>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="slug" class="block text-sm font-medium text-gray-700">Slug (URL)</label>
                            <input type="text" 
                                   name="slug" 
                                   id="slug" 
                                   x-model="slug"
                                   class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">Slug dibuat otomatis dari nama kategori.</p>
                            @error('slug')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('categories.index') }}" class="text-gray-600 hover:text-gray-900 mr-4 text-sm">
                                Batal
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-md text-sm">
                                Simpan Kategori
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>