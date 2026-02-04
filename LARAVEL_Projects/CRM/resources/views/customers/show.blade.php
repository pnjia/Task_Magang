<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail: {{ $customer->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="md:col-span-1">
                <div class="bg-white shadow-sm sm:rounded-lg p-6 sticky top-6">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Catat Aktivitas Baru</h3>
                    
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('customers.interactions.store', $customer->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Jenis Interaksi</label>
                            <select name="type" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="CALL">📞 Telepon</option>
                                <option value="MEETING">🤝 Meeting</option>
                                <option value="EMAIL">📧 Email</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Waktu</label>
                            <input type="datetime-local" name="occurred_at" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Durasi (Detik)</label>
                            <input type="number" name="duration_seconds" class="w-full border-gray-300 rounded-md shadow-sm" value="0">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Catatan Hasil</label>
                            <textarea name="notes" rows="3" class="w-full border-gray-300 rounded-md shadow-sm" placeholder="Apa hasil interaksinya?"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition">
                            Simpan Log
                        </button>
                    </form>
                </div>
            </div>

            <div class="md:col-span-2">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold mb-6">Riwayat Interaksi</h3>

                    <div class="space-y-6">
                        @forelse($customer->interactions as $interaction)
                            <div class="flex items-start border-l-4 {{ $interaction->type == 'CALL' ? 'border-blue-500' : ($interaction->type == 'MEETING' ? 'border-green-500' : 'border-yellow-500') }} pl-4 py-2">
                                <div class="flex-1">
                                    <div class="flex justify-between items-center">
                                        <h4 class="font-bold text-gray-800">
                                            {{ $interaction->type }} 
                                            <span class="text-xs font-normal text-gray-500 ml-2">
                                                oleh {{ $interaction->user->name ?? 'Unknown Sales' }}
                                            </span>
                                        </h4>
                                        <span class="text-sm text-gray-500">{{ $interaction->occurred_at->format('d M Y, H:i') }}</span>
                                    </div>
                                    <p class="text-gray-600 mt-1">{{ $interaction->notes }}</p>
                                    @if($interaction->duration_seconds > 0)
                                        <p class="text-xs text-gray-400 mt-1">⏱ Durasi: {{ $interaction->duration_seconds }} detik</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 italic">Belum ada riwayat interaksi dengan pelanggan ini.</p>
                        @endforelse
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>