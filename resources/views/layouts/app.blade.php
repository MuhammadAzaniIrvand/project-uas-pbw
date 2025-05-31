<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Penting untuk Laravel --}}

        {{-- Judul halaman dinamis (ambil dari layout lama Anda, lebih baik) --}}
        <title>@yield('title', config('app.name', 'LabSys'))</title>

        {{-- Favicon dan meta PWA (ambil dari layout lama Anda) --}}
        <link rel="icon" href="{{ asset('favicon.ico') }}" />
        <meta name="theme-color" content="#000000" />
        <meta name="description" content="@yield('meta_description', 'Deskripsi default aplikasi LabSys Anda')" />
        <link rel="apple-touch-icon" href="{{ asset('logo192.png') }}" />
        <link rel="manifest" href="{{ asset('manifest.json') }}" />

        <!-- Fonts (bisa menggunakan yang dari Jetstream atau font kustom Anda) -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        {{-- Jika Anda punya font kustom lain, tambahkan di sini atau di app.css --}}

        <!-- Scripts and Styles (Vite) -->
        @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- Pastikan ini memuat semua CSS dan JS Anda --}}

        <!-- Styles dari Jetstream (Livewire) dan untuk kustomisasi per halaman -->
        @livewireStyles
        @stack('styles') {{-- Untuk CSS spesifik per halaman (dari layout lama Anda) --}}
    </head>
    <body class="font-sans antialiased">
        <x-banner /> {{-- Komponen banner dari Jetstream --}}

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900"> {{-- Tambahkan dark mode jika perlu --}}
            {{-- Navigasi utama dari Jetstream (menu profil, dll.) --}}
            {{-- Anda bisa meng-custom komponen ini jika perlu tampilan berbeda --}}
            @livewire('navigation-menu')

            {{-- Jika Anda memiliki header/sidebar global dari layout lama, Anda bisa meletakkannya di sini --}}
            {{-- Contoh:
            @include('layouts.partials.custom-header')
            @include('layouts.partials.custom-sidebar')
            --}}

            <!-- Page Heading (Slot dari Jetstream) -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow"> {{-- Tambahkan dark mode --}}
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }} {{-- Ini akan diisi oleh view yang menggunakan layout ini, misal: <x-slot name="header">...</x-slot> --}}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            {{-- Konten utama dari layout lama Anda (`@yield('content')`) sekarang akan masuk ke `$slot` Jetstream --}}
            {{-- atau Anda bisa tetap menggunakan `@yield('content')` jika Anda tidak menggunakan slot `$header` Jetstream --}}
            <main class="py-4"> {{-- Ambil class dari layout lama Anda jika cocok --}}
                {{-- Jika view anak menggunakan @section('content'), gunakan @yield('content') --}}
                {{-- Jika view anak adalah komponen Livewire full page atau menggunakan slot seperti dashboard Jetstream, $slot lebih cocok --}}
                {{-- Pilihan 1: Menggunakan $slot (lebih umum jika mengikuti pola Jetstream Dashboard) --}}
                 {{ $slot }}

                {{-- Pilihan 2: Menggunakan @yield (jika halaman Anda lebih tradisional Blade) --}}
                {{-- @yield('content') --}}
            </main>

            {{-- Jika Anda memiliki footer global dari layout lama --}}
            {{-- @include('layouts.partials.custom-footer') --}}
        </div>

        @stack('modals') {{-- Untuk modal Jetstream --}}

        @livewireScripts
        @stack('scripts') {{-- Untuk skrip JavaScript tambahan (dari layout lama Anda) --}}
    </body>
</html>