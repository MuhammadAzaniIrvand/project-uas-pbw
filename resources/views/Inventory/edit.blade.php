<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Item: ') }} {{ $item->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <form method="POST" action="{{ route('admin.inventaris.update', $item) }}">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-label for="name" value="{{ __('Nama Item') }}" />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $item->name)" required autofocus />
                            <x-input-error for="name" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-label for="quantity" value="{{ __('Jumlah') }}" />
                            <x-input id="quantity" class="block mt-1 w-full" type="number" name="quantity" :value="old('quantity', $item->quantity)" required min="0" />
                            <x-input-error for="quantity" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-label for="category" value="{{ __('Kategori (Opsional)') }}" />
                            <x-input id="category" class="block mt-1 w-full" type="text" name="category" :value="old('category', $item->category)" />
                            <x-input-error for="category" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-label for="location" value="{{ __('Lokasi (Opsional)') }}" />
                            <x-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location', $item->location)" />
                            <x-input-error for="location" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-label for="description" value="{{ __('Deskripsi (Opsional)') }}" />
                            <textarea id="description" name="description" rows="3"
                                      class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">{{ old('description', $item->description) }}</textarea>
                            <x-input-error for="description" class="mt-2" />
                        </div>

                        {{-- Jika ada upload gambar:
                        <div class="mt-4">
                            <x-label for="image" value="{{ __('Gambar (Opsional)') }}" />
                            @if ($item->image_path)
                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="h-20 w-auto mb-2">
                            @endif
                            <input id="image" class="block mt-1 w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" type="file" name="image_path">
                            <x-input-error for="image_path" class="mt-2" />
                        </div>
                        --}}

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