<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Peminjaman Saya') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                    {{-- Pesan Sukses/Error Flash --}}
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

                    <h1 class="text-2xl font-medium text-gray-900 dark:text-white mb-6">
                        Daftar Peminjaman Barang Anda
                    </h1>

                    @if($borrowings->isEmpty())
                        <div class="text-center py-10">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Tidak Ada Riwayat Peminjaman</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Anda belum pernah melakukan peminjaman barang.</p>
                            <div class="mt-6">
                                <a href="{{ route('mahasiswa.inventory.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition">
                                    Lihat Inventaris & Pinjam Barang
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Barang</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tgl Pinjam</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harap Kembali</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tgl Kembali Aktual</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tujuan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Catatan Petugas</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($borrowings as $borrowing)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{-- Asumsi relasi inventaris() dan model Inventaris memiliki 'nama_alat' --}}
                                            {{ $borrowing->inventaris->nama_alat ?? 'Barang Tidak Ditemukan' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $borrowing->jumlah_pinjam }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $borrowing->tanggal_pinjam ? $borrowing->tanggal_pinjam->format('d M Y, H:i') : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $borrowing->tanggal_kembali_rencana ? $borrowing->tanggal_kembali_rencana->format('d M Y') : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($borrowing->status == 'Menunggu Persetujuan') bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100
                                                @elseif($borrowing->status == 'Dipinjam') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100
                                                @elseif($borrowing->status == 'Ditolak') bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100
                                                @elseif($borrowing->status == 'Dikembalikan') bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100
                                                @elseif($borrowing->status == 'Terlambat') bg-pink-100 text-pink-800 dark:bg-pink-700 dark:text-pink-100
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100
                                                @endif">
                                                {{ $borrowing->status ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $borrowing->tanggal_kembali_aktual ? $borrowing->tanggal_kembali_aktual->format('d M Y, H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $borrowing->tujuan_peminjaman ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $borrowing->catatan_petugas ?? $borrowing->catatan_pengembalian ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6">
                            {{ $borrowings->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>