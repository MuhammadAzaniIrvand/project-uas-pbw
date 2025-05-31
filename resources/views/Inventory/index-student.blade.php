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

                    {{-- Tambahkan form filter jika Anda mengirimkan $kategoris dari controller --}}
                    {{-- Contoh:
                    <form method="GET" action="{{ route('mahasiswa.inventory.index') }}" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-md">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-label for="search_student_inventory" value="Cari Nama Alat" />
                                <x-input id="search_student_inventory" class="block mt-1 w-full" type="text" name="search" :value="request('search')" placeholder="Masukkan nama alat..."/>
                            </div>
                            <div>
                                <x-label for="kategori_id_filter_student" value="Kategori" />
                                <select name="kategori_id_filter" id="kategori_id_filter_student" class="block mt-1 w-full border-gray-300 ...">
                                    <option value="">Semua Kategori</option>
                                    @if(isset($kategoris))
                                        @foreach ($kategoris as $kat)
                                            <option value="{{ $kat->id }}" {{ request('kategori_id_filter') == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="flex items-end">
                                <x-button type="submit">Filter</x-button>
                                <a href="{{ route('mahasiswa.inventory.index') }}" class="ml-3 ...">Reset</a>
                            </div>
                        </div>
                    </form>
                    --}}

                    @if($items->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">Saat ini tidak ada item yang tersedia untuk dipinjam.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            {{-- Ingat: $items adalah koleksi objek Inventaris --}}
                            @foreach($items as $inventaris_item) {{-- Ganti $item menjadi $inventaris_item agar lebih jelas --}}
                            <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                                {{-- Jika ada gambar (sesuaikan nama kolom path gambar Anda)
                                @if($inventaris_item->path_gambar)
                                    <img src="{{ asset('storage/' . $inventaris_item->path_gambar) }}" alt="{{ $inventaris_item->nama_alat }}" class="w-full h-40 object-cover rounded-md mb-4">
                                @else
                                    <div class="w-full h-40 bg-gray-200 dark:bg-gray-600 rounded-md mb-4 flex items-center justify-center text-gray-400 dark:text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-image-off"><line x1="2" x2="22" y1="2" y2="22"/><path d="M10.41 10.41a2 2 0 1 1-2.83-2.83"/><line x1="14" x2="14.01" y1="4" y2="4"/><line x1="4" x2="4.01" y1="14" y2="14"/><line x1="20" x2="20.01" y1="14" y2="14"/><line x1="14" x2="14.01" y1="20" y2="20"/><line x1="4" x2="4.01" y1="20" y2="20"/></svg>
                                        <span class="ml-2">Gambar Tidak Tersedia</span>
                                    </div>
                                @endif
                                --}}
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1 truncate" title="{{ $inventaris_item->nama_alat }}">
                                    {{ $inventaris_item->nama_alat ?? 'Nama Tidak Diketahui' }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Kategori: {{ $inventaris_item->kategori->nama_kategori ?? ($inventaris_item->kategori_id ? 'ID Kategori: '.$inventaris_item->kategori_id : 'Tidak Berkategori') }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Lokasi: {{ $inventaris_item->lokasi ?? '-' }}
                                </p>
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Stok Tersedia: <span class="text-blue-600 dark:text-blue-400">{{ $inventaris_item->jumlah ?? '0' }}</span>
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-2 h-10 overflow-hidden">
                                    {{ Str::limit($inventaris_item->deskripsi, 80) ?? 'Tidak ada deskripsi.' }}
                                </p>
                                <div class="mt-4">
                                    {{-- Pastikan parameter route model binding di web.php adalah {inventaris} --}}
                                    <a href="{{ route('mahasiswa.inventory.show', $inventaris_item) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-800">
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