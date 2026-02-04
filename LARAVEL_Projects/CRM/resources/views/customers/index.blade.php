<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Pelanggan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-700">Database Pelanggan</h3>
                    <a href="{{ route('customers.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition font-semibold">
                        + Tambah Pelanggan
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telepon</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($customers as $customer)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $customer->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $customer->phone_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $customer->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap flex gap-4">

                                <a href="{{ route('customers.edit', $customer->id) }}" class="text-yellow-600 hover:text-yellow-900 font-bold">
                                    Edit
                                </a>
                                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" 
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini? Semua riwayat interaksi juga akan hilang.');">
                                @csrf
                                @method('DELETE') <button type="submit" class="text-red-600 hover:text-red-900 font-bold">
                                    Hapus
                                </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('customers.show', $customer->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">
                                    Buka Detail & Log
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                
                @if($customers->isEmpty())
                    <p class="text-center mt-4 text-gray-500">Belum ada data pelanggan.</p>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>