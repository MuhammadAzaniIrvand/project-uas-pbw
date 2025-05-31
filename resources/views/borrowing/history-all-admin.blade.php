<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Semua Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 dark:bg-green-700 dark:text-green-100 dark:border-green-600" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 dark:bg-red-700 dark:text-red-100 dark:border-red-600" role="alert">
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <h1 class="text-2xl font-medium text-gray-900 dark:text-white mb-6">
                        Daftar Seluruh Peminjaman Barang
                    </h1>

                    <form method="GET" action="{{ route('admin.borrowing.history.all') }}" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-md">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <x-label for="search_peminjaman" value="Cari (Nama Barang/Peminjam/NPM)" />
                                <x-input id="search_peminjaman" class="block mt-1 w-full" type="text" name="search_peminjaman" :value="request('search_peminjaman')" placeholder="Masukkan kata kunci..."/>
                            </div>
                            <div>
                                <x-label for="status_peminjaman" value="Status Peminjaman" />
                                <select id="status_peminjaman" name="status_peminjaman" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    @if(isset($statuses))
                                        @foreach ($statuses as $statusValue)
                                            <option value="{{ $statusValue }}" {{ request('status_peminjaman') == $statusValue ? 'selected' : '' }}>{{ $statusValue }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="flex items-end">
                                <x-button type="submit">
                                    Filter
                                </x-button>
                                <a href="{{ route('admin.borrowing.history.all') }}" class="ml-3 inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    @if(!isset($borrowings) || $borrowings->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">Tidak ada data peminjaman yang cocok dengan filter atau belum ada peminjaman sama sekali.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Peminjam (NPM)</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Barang</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tgl Pinjam</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harap Kembali</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tgl Kembali Aktual</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Petugas Proses</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($borrowings as $borrowing)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $borrowing->user->name ?? 'N/A' }} <br> ({{ $borrowing->user->npm ?? 'N/A' }})
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $borrowing->inventaris->nama_alat ?? 'Barang Tidak Ditemukan' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $borrowing->jumlah_pinjam }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $borrowing->tanggal_pinjam ? \Carbon\Carbon::parse($borrowing->tanggal_pinjam)->format('d M Y, H:i') : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $borrowing->tanggal_kembali_rencana ? \Carbon\Carbon::parse($borrowing->tanggal_kembali_rencana)->format('d M Y') : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($borrowing->status == 'Menunggu Persetujuan') bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100
                                                @elseif($borrowing->status == 'Dipinjam') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100
                                                @elseif($borrowing->status == 'Ditolak') bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100
                                                @elseif($borrowing->status == 'Dikembalikan') bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100
                                                @elseif($borrowing->status == 'Terlambat') bg-pink-100 text-pink-800 dark:bg-pink-700 dark:text-pink-100
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100 @endif">
                                                {{ $borrowing->status ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $borrowing->tanggal_kembali_aktual ? \Carbon\Carbon::parse($borrowing->tanggal_kembali_aktual)->format('d M Y, H:i') : '-' }}
                                        </td>
                                         <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $borrowing->petugas->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if(in_array($borrowing->status, ['Dipinjam', 'Terlambat']))
                                                <button type="button" onclick="openReturnModal({{ $borrowing->id }}, '{{ addslashes($borrowing->inventaris->nama_alat ?? 'Item Tidak Diketahui') }}')" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-200">
                                                    Tandai Kembali
                                                </button>
                                            @else
                                                -
                                            @endif
                                            {{-- <a href="{{ route('admin.borrowing.show', $borrowing) }}" class="text-indigo-600 hover:text-indigo-900 ml-2">Detail</a> --}}
                                        </td>
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

    {{-- Modal untuk Tandai Kembali --}}
    <div id="returnModal" x-data="{ showReturnModal: false, borrowingIdReturn: null, itemNameReturn: '' }" x-show="showReturnModal" @keydown.escape.window="showReturnModal = false" style="display:none;" class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title-return" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showReturnModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
            <div x-show="showReturnModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form :action="'{{ url('admin/permintaan-peminjaman') }}/' + borrowingIdReturn + '/return'" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-700 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title-return">
                                    Tandai Pengembalian Barang
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Anda akan menandai barang "<span x-text="itemNameReturn" class="font-semibold"></span>" telah dikembalikan. Stok akan dikembalikan ke sistem.
                                    </p>
                                    <div class="mt-4">
                                        <x-label for="catatan_petugas_return_modal" value="Catatan Pengembalian (Opsional)" />
                                        <textarea id="catatan_petugas_return_modal" name="catatan_petugas" rows="3" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full"></textarea>
                                        <x-input-error for="catatan_petugas" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Ya, Tandai Kembali
                        </button>
                        <button @click="showReturnModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
    function openReturnModal(borrowingId, itemName) {
        console.log('openReturnModal dipanggil:', borrowingId, itemName);
        let modalElement = document.getElementById('returnModal'); // Menggunakan ID unik modal
        if (modalElement && modalElement.__x) {
            let modalData = modalElement.__x.$data;
            modalData.borrowingIdReturn = borrowingId;
            modalData.itemNameReturn = itemName;
            modalData.showReturnModal = true;
            console.log('showReturnModal di-set ke true');
        } else {
            console.error('Elemen modal "returnModal" tidak ditemukan atau Alpine tidak terpasang.');
            // alert('Error: Komponen modal "Tandai Kembali" tidak ditemukan.'); // Bisa di-uncomment untuk debug lebih lanjut
        }
    }
</script>
@endpush