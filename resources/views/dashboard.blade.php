<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            {{-- Tombol Aksi Cepat (Contoh untuk Admin/Aslab) --}}
            @if(in_array(Auth::user()->role, ['Admin', 'Aslab']))
                <div>
                    <a href="{{ route('admin.inventaris.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                        + Tambah Inventaris
                    </a>
                    {{-- Tambahkan tombol lain jika perlu, misal + Tambah Peminjaman Manual --}}
                </div>
            @elseif(Auth::user()->role === 'Mahasiswa')
                 <div>
                    <a href="{{ route('mahasiswa.inventory.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition ease-in-out duration-150">
                        Pinjam Alat
                    </a>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Pesan Flash --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            {{-- Welcome Message --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg mb-6">
                <div class="p-6 sm:px-10 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="mt-2 text-2xl text-gray-900 dark:text-white">
                        Selamat datang kembali, {{ $user->name }}!
                    </div>
                    <div class="mt-1 text-gray-600 dark:text-gray-400">
                        Anda login sebagai {{ $user->role }}.
                    </div>
                </div>
            </div>

            {{-- KONTEN DASHBOARD BERDASARKAN ROLE --}}
            @if(in_array($user->role, ['Admin', 'Aslab']))
                {{-- DASHBOARD ADMIN/ASLAB --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    {{-- Card Total Pengguna --}}
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-500 bg-opacity-20 text-blue-600 dark:text-blue-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Total Pengguna</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalUsers ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Card Total Inventaris --}}
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <div class="flex items-center">
                             <div class="p-3 rounded-full bg-green-500 bg-opacity-20 text-green-600 dark:text-green-300">
                               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Jenis Inventaris</p> {{-- Atau Total Unit jika $totalInventaris adalah sum(jumlah) --}}
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalInventaris ?? 0 }}</p>
                                {{-- <a href="{{ route('admin.inventaris.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Lihat Detail</a> --}}
                            </div>
                        </div>
                    </div>

                     {{-- Card Permintaan Pending --}}
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <div class="flex items-center">
                             <div class="p-3 rounded-full bg-yellow-500 bg-opacity-20 text-yellow-600 dark:text-yellow-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="M12 6v6l4 2"/></svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Permintaan Pending</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $permintaanPending ?? 0 }}</p>
                                <a href="{{ route('admin.borrowing.requests') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Lihat Detail</a>
                            </div>
                        </div>
                    </div>

                    {{-- Card Peminjaman Aktif --}}
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <div class="flex items-center">
                             <div class="p-3 rounded-full bg-red-500 bg-opacity-20 text-red-600 dark:text-red-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Peminjaman Aktif</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $peminjamanAktif ?? 0 }}</p>
                                <a href="{{ route('admin.borrowing.history.all', ['status_peminjaman' => 'Dipinjam']) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                    {{-- Tambahkan card lain: Peminjaman Terlambat, Total Laboratorium, dll. --}}
                </div>

                {{-- Area Chart (Contoh) --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Aktivitas Pinjam Alat (7 Hari)</h3>
                        <canvas id="borrowingActivityChart"></canvas>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Aktivitas Booking Lab (7 Hari)</h3>
                        <canvas id="labBookingActivityChart"></canvas>
                    </div>
                </div>

            @elseif($user->role === 'Mahasiswa')
                {{-- DASHBOARD MAHASISWA --}}
                 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    {{-- Card Peminjaman Aktif Saya --}}
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <div class="flex items-center">
                             <div class="p-3 rounded-full bg-green-500 bg-opacity-20 text-green-600 dark:text-green-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Peminjaman Aktif Saya</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $peminjamanAktifSaya ?? 0 }}</p>
                                <a href="{{ route('mahasiswa.borrowing.history', ['status' => 'Dipinjam']) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Lihat Detail</a>
                            </div>
                        </div>
                    </div>

                    {{-- Card Permintaan Pending Saya --}}
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <div class="flex items-center">
                             <div class="p-3 rounded-full bg-yellow-500 bg-opacity-20 text-yellow-600 dark:text-yellow-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="M12 6v6l4 2"/></svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Permintaan Pending Saya</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $permintaanPendingSaya ?? 0 }}</p>
                                <a href="{{ route('mahasiswa.borrowing.history', ['status' => 'Menunggu Persetujuan']) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Lihat Detail</a>
                            </div>
                        </div>
                    </div>

                    {{-- Card Total Peminjaman Disetujui (Riwayat) --}}
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                        <div class="flex items-center">
                             <div class="p-3 rounded-full bg-blue-500 bg-opacity-20 text-blue-600 dark:text-blue-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase">Total Alat Pernah Dipinjam</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalPeminjamanDisetujui ?? 0 }}</p>
                                <a href="{{ route('mahasiswa.borrowing.history') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Lihat Semua Riwayat</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    @push('scripts')
        {{-- Hanya load Chart.js jika admin/aslab (karena mahasiswa tidak ada chart di contoh ini) --}}
        @if(in_array(Auth::user()->role, ['Admin', 'Aslab']))
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    // Data dari PHP (pastikan formatnya benar untuk Chart.js)
                    const borrowingData = @json($aktivitasPeminjaman7Hari ?? []); // Pastikan ada fallback array kosong
                    const labBookingData = @json($aktivitasBookingLab7Hari ?? []);

                    const borrowingLabels = borrowingData.map(item => item.date);
                    const borrowingCounts = borrowingData.map(item => item.count);

                    const labBookingLabels = labBookingData.map(item => item.date);
                    const labBookingCounts = labBookingData.map(item => item.count);

                    // Chart Aktivitas Peminjaman
                    const borrowingCtx = document.getElementById('borrowingActivityChart');
                    if (borrowingCtx) {
                        new Chart(borrowingCtx, {
                            type: 'line', // atau 'bar'
                            data: {
                                labels: borrowingLabels,
                                datasets: [{
                                    label: 'Peminjaman Alat',
                                    data: borrowingCounts,
                                    borderColor: 'rgb(59, 130, 246)', // biru
                                    tension: 0.1,
                                    fill: false
                                }]
                            },
                            options: { scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
                        });
                    }

                    // Chart Aktivitas Booking Lab
                    const labBookingCtx = document.getElementById('labBookingActivityChart');
                    if (labBookingCtx) {
                        new Chart(labBookingCtx, {
                            type: 'line', // atau 'bar'
                            data: {
                                labels: labBookingLabels,
                                datasets: [{
                                    label: 'Booking Lab',
                                    data: labBookingCounts,
                                    borderColor: 'rgb(236, 72, 153)', // pink
                                    tension: 0.1,
                                    fill: false
                                }]
                            },
                            options: { scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
                        });
                    }
                });
            </script>
        @endif
    @endpush
</x-app-layout>