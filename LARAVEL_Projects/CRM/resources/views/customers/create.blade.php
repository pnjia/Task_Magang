<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Pelanggan Baru
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Perusahaan / Pelanggan</label>
                        <input type="text" name="name" class="w-full border-gray-300 rounded-md shadow-sm" required placeholder="PT Maju Mundur">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Telepon / WA</label>
                        <input type="text" name="phone_number" class="w-full border-gray-300 rounded-md shadow-sm" required placeholder="0812...">
                        @error('phone_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Email (Opsional)</label>
                        <input type="email" name="email" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="email@contoh.com">
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center justify-end gap-4">
                        <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Batal</a>
                        <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded hover:bg-blue-700 transition">
                            Simpan Data
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>