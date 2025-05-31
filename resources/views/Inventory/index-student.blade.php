<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Inventaris Laboratorium') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                    <h1 class="text-2xl font-medium text-gray-900 dark:text-white mb-6">
                        Item yang Tersedia untuk Dipinjam
                    </h1>

                    @if($items->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">Saat ini tidak ada item yang tersedia untuk dipinjam.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($items as $item)
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg shadow">
                                {{-- Jika ada gambar
                                @if($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="w-full h-40 object-cover rounded-md mb-4">
                                @else
                                    <div class="w-full h-40 bg-gray-200 dark:bg-gray-600 rounded-md mb-4 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 dark:text-gray-500 lucide lucide-package"><path d="M16.5 9.4 7.55 4.24"/><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" x2="12" y1="22" y2="12"/></svg>
                                    </div>
                                @endif
                                --}}
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $item->name }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kategori: {{ $item->category ?? '-' }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Lokasi: {{ $item->location ?? '-' }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Stok: {{ $item->quantity }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-2 truncate">{{ $item->description ?? 'Tidak ada deskripsi.' }}</p>
                                <div class="mt-4">
                                    <a href="{{ route('mahasiswa.inventory.show', $item) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-700">
                                        Lihat Detail & Pinjam
                                        <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/></svg>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-6">
                            {{ $items->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>