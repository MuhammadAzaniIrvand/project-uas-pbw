<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{-- GANTI $item->name menjadi $inventaris->nama_alat --}}
            {{ __('Edit Item: ') }} {{ $inventaris->nama_alat }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <x-validation-errors class="mb-4" />

                    {{-- GANTI $item menjadi $inventaris di action form --}}
                    <form method="POST" action="{{ route('admin.inventaris.update', $inventaris) }}">
                        @csrf
                        @method('PUT')

                        {{-- NAMA ALAT --}}
                        <div>
                            <x-label for="nama_alat" value="{{ __('Nama Alat/Item') }}" />
                            {{-- GANTI $item menjadi $inventaris untuk old() dan value --}}
                            <x-input id="nama_alat" class="block mt-1 w-full" type="text" name="nama_alat" :value="old('nama_alat', $inventaris->nama_alat)" required autofocus />
                            <x-input-error for="nama_alat" class="mt-2" />
                        </div>

                        {{-- JUMLAH --}}
                        <div class="mt-4">
                            <x-label for="jumlah" value="{{ __('Jumlah') }}" />
                            {{-- GANTI $item menjadi $inventaris --}}
                            <x-input id="jumlah" class="block mt-1 w-full" type="number" name="jumlah" :value="old('jumlah', $inventaris->jumlah)" required min="0" />
                            <x-input-error for="jumlah" class="mt-2" />
                        </div>

                        {{-- KATEGORI --}}
                        <div class="mt-4">
                            <x-label for="kategori_id" value="{{ __('Kategori') }}" />
                            <select name="kategori_id" id="kategori_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">-- Pilih Kategori (Opsional) --</option>
                                @if(isset($kategoris) && $kategoris->count() > 0)
                                    @foreach ($kategoris as $kategori)
                                        {{-- GANTI $item menjadi $inventaris --}}
                                        <option value="{{ $kategori->id }}" {{ old('kategori_id', $inventaris->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach
                                @else
                                     <option value="" disabled>Tidak ada data kategori</option>
                                @endif
                            </select>
                            <x-input-error for="kategori_id" class="mt-2" />
                        </div>

                        {{-- KONDISI --}}
                        <div class="mt-4">
                            <x-label for="kondisi" value="{{ __('Kondisi') }}" />
                            <select name="kondisi" id="kondisi" required class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">-- Pilih Kondisi --</option>
                                @if(isset($kondisiOptions))
                                    @foreach ($kondisiOptions as $opsiKondisi)
                                        {{-- GANTI $item menjadi $inventaris --}}
                                        <option value="{{ $opsiKondisi }}" {{ old('kondisi', $inventaris->kondisi) == $opsiKondisi ? 'selected' : '' }}>
                                            {{ $opsiKondisi }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <x-input-error for="kondisi" class="mt-2" />
                        </div>

                        {{-- LOKASI --}}
                        <div class="mt-4">
                            <x-label for="lokasi" value="{{ __('Lokasi (Opsional)') }}" />
                            {{-- GANTI $item menjadi $inventaris --}}
                            <x-input id="lokasi" class="block mt-1 w-full" type="text" name="lokasi" :value="old('lokasi', $inventaris->lokasi)" />
                            <x-input-error for="lokasi" class="mt-2" />
                        </div>

                        {{-- DESKRIPSI --}}
                        <div class="mt-4">
                            <x-label for="deskripsi" value="{{ __('Deskripsi (Opsional)') }}" />
                            {{-- GANTI $item menjadi $inventaris --}}
                            <textarea id="deskripsi" name="deskripsi" rows="3"
                                      class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">{{ old('deskripsi', $inventaris->deskripsi) }}</textarea>
                            <x-input-error for="deskripsi" class="mt-2" />
                        </div>

                        {{-- NOMOR SERI --}}
                        <div class="mt-4">
                            <x-label for="nomor_seri" value="{{ __('Nomor Seri (Opsional)') }}" />
                            {{-- GANTI $item menjadi $inventaris --}}
                            <x-input id="nomor_seri" class="block mt-1 w-full" type="text" name="nomor_seri" :value="old('nomor_seri', $inventaris->nomor_seri)" />
                            <x-input-error for="nomor_seri" class="mt-2" />
                        </div>

                        {{-- TANGGAL PENGADAAN --}}
                        <div class="mt-4">
                            <x-label for="tanggal_pengadaan" value="{{ __('Tanggal Pengadaan (Opsional)') }}" />
                            {{-- GANTI $item menjadi $inventaris --}}
                            <x-input id="tanggal_pengadaan" class="block mt-1 w-full" type="date" name="tanggal_pengadaan" :value="old('tanggal_pengadaan', $inventaris->tanggal_pengadaan)" />
                            <x-input-error for="tanggal_pengadaan" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('admin.inventaris.index') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4">
                                Batal
                            </a>
                            <x-button>
                                {{ __('Update Item') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>