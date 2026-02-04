<x-app-layout>
    <x-slot name="header">
        {{ __('Edit Kategori') }}
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Edit Kategori</h3>
                    </div>

                    <form action="{{ route('categories.update', $category->id) }}" method="POST" x-data="{ name: '{{ $category->name }}', slug: '{{ $category->slug }}' }">
                        @csrf
                        @method('PUT') <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                            <input type="text" name="name" id="name" x-model="name"
                                   @input="slug = name.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '')"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   required>
                        </div>

                        <div class="mb-4">
                            <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                            <input type="text" name="slug" id="slug" x-model="slug"
                                   class="mt-1 block w-full rounded-md border-gray-300 bg-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   required>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('categories.index') }}" class="text-gray-600 hover:text-gray-900 mr-4 text-sm">Batal</a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow-md text-sm">
                                Update Kategori
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>