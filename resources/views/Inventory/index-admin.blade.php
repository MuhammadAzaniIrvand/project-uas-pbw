<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Inventaris') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> {{-- max-w-full jika ingin tabel lebih lebar --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-medium text-gray-900 dark:text-white">
                            Daftar Item Inventaris
                        </h1>
                        <a href="{{ route('admin.inventaris.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition">
                            {{ __('Tambah Item Baru') }}
                        </a>
                    </div>

                    {{-- Tambahkan Form Filter di sini jika Anda mau (dari controller) --}}
                    {{-- Contoh:
                    <form method="GET" action="{{ route('admin.inventaris.index') }}" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-md">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <x-label for="search" value="Cari" />
                                <x-input id="search" class="block mt-1 w-full" type="text" name="search" :value="request('search')" />
                            </div>
                            <div>
                                <x-label for="kategori_id_filter" value="Kategori" />
                                <select name="kategori_id_filter" id="kategori_id_filter" class="block mt-1 w-full border-gray-300 ...">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($kategoris as $kat)
                                        <option value="{{ $kat->id }}" {{ request('kategori_id_filter') == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-label for="kondisi_filter" value="Kondisi" />
                                <select name="kondisi_filter" id="kondisi_filter" class="block mt-1 w-full border-gray-300 ...">
                                    @foreach ($kondisiOptions as $kond)
                                        <option value="{{ $kond }}" {{ request('kondisi_filter') == $kond ? 'selected' : '' }}>{{ $kond }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-end">
                                <x-button type="submit">Filter</x-button>
                            </div>
                        </div>
                    </form>
                    --}}

                    @if($items->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">Belum ada item inventaris yang cocok atau belum ada data.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        {{-- Sesuaikan header tabel --}}
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Alat</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kategori</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kondisi</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Lokasi</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No. Seri</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($items as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $item->nama_alat ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{-- Akses nama kategori melalui relasi --}}
                                            {{ $item->kategori->nama_kategori ?? ($item->kategori_id ? 'ID: '.$item->kategori_id : '-') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $item->kondisi ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $item->jumlah ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $item->lokasi ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $item->nomor_seri ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            {{-- Pastikan parameter route model binding di web.php adalah {inventaris} --}}
                                            <a href="{{ route('admin.inventaris.edit', $item) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 mr-3">Edit</a>
                                            <form action="{{ route('admin.inventaris.destroy', $item) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus item ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $items->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>