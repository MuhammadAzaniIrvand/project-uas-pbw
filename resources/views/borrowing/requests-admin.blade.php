<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Permintaan Peminjaman Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> {{-- Atau max-w-full jika tabel sangat lebar --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                    {{-- Pesan Sukses/Error Flash --}}
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
                        Daftar Permintaan Peminjaman (Menunggu Persetujuan)
                    </h1>

                    {{-- Form Filter (jika ada variabel $requests dan Anda ingin filter) --}}
                    <form method="GET" action="{{ route('admin.borrowing.requests') }}" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-md">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-label for="search_requests" value="Cari (Nama Barang/Peminjam/NPM)" />
                                <x-input id="search_requests" class="block mt-1 w-full" type="text" name="search_requests" :value="request('search_requests')" placeholder="Masukkan kata kunci..."/>
                            </div>
                            <div class="flex items-end">
                                <x-button type="submit">
                                    Filter
                                </x-button>
                                <a href="{{ route('admin.borrowing.requests') }}" class="ml-3 inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    @if(!isset($requests) || $requests->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">Tidak ada permintaan peminjaman yang menunggu persetujuan saat ini.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Peminjam (NPM)</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Barang</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tgl Permintaan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harap Kembali</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tujuan</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($requests as $peminjaman)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ $peminjaman->user->name ?? 'N/A' }} <br> ({{ $peminjaman->user->npm ?? 'N/A' }})
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $peminjaman->inventaris->nama_alat ?? 'Barang Tidak Ditemukan' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $peminjaman->jumlah_pinjam }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $peminjaman->created_at ? $peminjaman->created_at->format('d M Y, H:i') : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $peminjaman->tanggal_kembali_rencana ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana)->format('d M Y') : '-' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate" title="{{ $peminjaman->tujuan_peminjaman }}">{{ Str::limit($peminjaman->tujuan_peminjaman, 50) ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <form action="{{ route('admin.borrowing.approve', $peminjaman) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-200 font-semibold" onclick="return confirm('Anda yakin ingin menyetujui permintaan ini?')">
                                                    Setujui
                                                </button>
                                            </form>
                                            <span class="text-gray-300 dark:text-gray-600 mx-1">|</span>
                                            <button type="button" onclick="openRejectModal({{ $peminjaman->id }}, '{{ addslashes($peminjaman->user->name ?? 'User Tidak Diketahui') }}', '{{ addslashes($peminjaman->inventaris->nama_alat ?? 'Item Tidak Diketahui') }}')" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200 font-semibold">
                                                Tolak
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-6">
                            {{ $requests->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal untuk Tolak Permintaan --}}
    <div id="rejectModal" x-data="{ showRejectModal: false, borrowingIdReject: null, userNameReject: '', itemNameReject: '' }" x-show="showRejectModal" @keydown.escape.window="showRejectModal = false" style="display:none;" class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title-reject" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showRejectModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
            <div x-show="showRejectModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form :action="'{{ url('admin/permintaan-peminjaman') }}/' + borrowingIdReject + '/reject'" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-700 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title-reject">
                                    Tolak Permintaan Peminjaman
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Anda akan menolak permintaan dari <strong x-text="userNameReject"></strong> untuk barang "<span x-text="itemNameReject" class="font-semibold"></span>".
                                    </p>
                                    <div class="mt-4">
                                        <x-label for="catatan_petugas_reject_modal" value="Alasan Penolakan (Opsional)" />
                                        <textarea id="catatan_petugas_reject_modal" name="catatan_petugas" rows="3" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" placeholder="Masukkan alasan penolakan..."></textarea>
                                        <x-input-error for="catatan_petugas" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Ya, Tolak Permintaan
                        </button>
                        <button @click="showRejectModal = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
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
    function openRejectModal(borrowingId, userName, itemName) {
        console.log('openRejectModal dipanggil:', borrowingId, userName, itemName);
        let modalElement = document.getElementById('rejectModal'); // Menggunakan ID unik modal
        if (modalElement && modalElement.__x) {
            let modalData = modalElement.__x.$data;
            modalData.borrowingIdReject = borrowingId;
            modalData.userNameReject = userName;
            modalData.itemNameReject = itemName;
            modalData.showRejectModal = true;
            console.log('showRejectModal di-set ke true');
        } else {
            console.error('Elemen modal "rejectModal" tidak ditemukan atau Alpine tidak terpasang.');
            alert('Error: Komponen modal "Tolak" tidak ditemukan.');
        }
    }
</script>
@endpush