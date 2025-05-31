<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{-- Controller mengirimkan $inventaris --}}
            Detail Item: {{ $inventaris->nama_alat ?? 'Tidak Diketahui' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    {{-- Tombol Kembali --}}
                    <div class="mb-6">
                        <a href="{{ route('mahasiswa.inventory.index') }}" class="inline-flex items-center text-sm text-blue-600 dark:text-blue-400 hover:underline">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1 h-4 w-4"><path d="m15 18-6-6 6-6"/></svg>
                            Kembali ke Daftar Inventaris
                        </a>
                    </div>

                    {{-- Detail Item --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            {{-- Gambar Placeholder atau Gambar Asli --}}
                            <div class="w-full h-64 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center text-gray-400 dark:text-gray-500 mb-4">
                                {{-- @if($inventaris->path_gambar)
                                    <img src="{{ asset('storage/' . $inventaris->path_gambar) }}" alt="{{ $inventaris->nama_alat }}" class="max-h-full max-w-full object-contain rounded-lg">
                                @else --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-archive"><rect width="20" height="5" x="2" y="3" rx="1"/><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/><path d="M10 12h4"/></svg>
                                {{-- @endif --}}
                            </div>
                        </div>
                        <div>
                            <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-3">{{ $inventaris->nama_alat ?? 'Nama Tidak Diketahui' }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-1"><span class="font-semibold">Kategori:</span> {{ $inventaris->kategori->nama_kategori ?? 'Tidak Berkategori' }}</p>
                            <p class="text-gray-600 dark:text-gray-400 mb-1"><span class="font-semibold">Lokasi:</span> {{ $inventaris->lokasi ?? '-' }}</p>
                            <p class="text-gray-600 dark:text-gray-400 mb-1"><span class="font-semibold">Kondisi:</span> {{ $inventaris->kondisi ?? '-' }}</p>
                            <p class="text-gray-600 dark:text-gray-400 mb-1"><span class="font-semibold">Nomor Seri:</span> {{ $inventaris->nomor_seri ?? '-' }}</p>
                            <p class="text-gray-600 dark:text-gray-400 mb-3"><span class="font-semibold">Tanggal Pengadaan:</span> {{ $inventaris->tanggal_pengadaan ? \Carbon\Carbon::parse($inventaris->tanggal_pengadaan)->format('d M Y') : '-' }}</p>
                            <p class="text-lg font-bold {{ $inventaris->jumlah > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                Stok Tersedia: {{ $inventaris->jumlah ?? '0' }}
                            </p>
                            <div class="mt-4 prose dark:prose-invert max-w-none text-sm text-gray-700 dark:text-gray-300">
                                <h4 class="font-semibold text-gray-800 dark:text-gray-100">Deskripsi:</h4>
                                <p>{{ $inventaris->deskripsi ?? 'Tidak ada deskripsi untuk item ini.' }}</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-8 border-gray-200 dark:border-gray-700">

                    {{-- Form Peminjaman --}}
                    @if ($inventaris->jumlah > 0)
                        <h4 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Formulir Peminjaman</h4>

                        {{-- Pesan error spesifik untuk form ini --}}
                        @if(session('error_pinjam'))
                            <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                                <p>{{ session('error_pinjam') }}</p>
                            </div>
                        @endif
                        <x-validation-errors class="mb-4" /> {{-- Untuk error dari $request->validate() --}}

                        <form method="POST" action="{{ route('mahasiswa.borrow.request', $inventaris) }}">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <x-label for="jumlah_pinjam" value="Jumlah yang Ingin Dipinjam (Maks: {{ $inventaris->jumlah }})" />
                                    <x-input id="jumlah_pinjam" class="block mt-1 w-full md:w-1/2" type="number" name="jumlah_pinjam" :value="old('jumlah_pinjam', 1)" required min="1" max="{{ $inventaris->jumlah }}" />
                                    <x-input-error for="jumlah_pinjam" class="mt-2" />
                                </div>

                                <div>
                                    <x-label for="tanggal_kembali_rencana" value="Tanggal Rencana Pengembalian" />
                                    <x-input id="tanggal_kembali_rencana" class="block mt-1 w-full md:w-1/2" type="date" name="tanggal_kembali_rencana" :value="old('tanggal_kembali_rencana')" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" />
                                    <x-input-error for="tanggal_kembali_rencana" class="mt-2" />
                                </div>

                                <div>
                                    <x-label for="tujuan_peminjaman" value="Tujuan Peminjaman" />
                                    <textarea id="tujuan_peminjaman" name="tujuan_peminjaman" rows="4" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" required>{{ old('tujuan_peminjaman') }}</textarea>
                                    <x-input-error for="tujuan_peminjaman" class="mt-2" />
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <x-button>
                                    {{ __('Ajukan Peminjaman') }}
                                </x-button>
                            </div>
                        </form>
                    @else
                        <div class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-700/30 border-l-4 border-yellow-400 dark:border-yellow-500 text-yellow-700 dark:text-yellow-200">
                            <p class="font-medium">Stok item ini sedang habis dan tidak dapat dipinjam saat ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>