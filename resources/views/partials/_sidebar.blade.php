<!-- Sidebar -->
<div x-data="{ open: true }" class="flex flex-col w-64 bg-gray-800 text-gray-100">
    <!-- Tombol Toggle Sidebar (untuk mobile) -->
    <div class="flex items-center justify-between p-4 h-16 border-b border-gray-700 md:justify-center">
        <a href="{{ route('dashboard') }}" class="text-xl font-bold">LabSys</a>
        <button @click="open = !open" class="md:hidden text-gray-400 hover:text-white focus:outline-none focus:text-white">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>
    </div>

    <!-- Menu -->
    <nav :class="{'block': open, 'hidden': !open}" class="flex-grow md:block px-2 pb-4 space-y-1">
        <a href="{{ route('dashboard') }}" class="block px-2 py-2 mt-2 text-sm font-medium rounded-md hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-900' : '' }}">
            Dashboard
        </a>

        @if(Auth::user()->isAslab() || Auth::user()->isAdmin())
            <a href="{{ route('aslab.inventaris.index') }}" class="block px-2 py-2 mt-1 text-sm font-medium rounded-md hover:bg-gray-700 {{ request()->routeIs('aslab.inventaris.*') ? 'bg-gray-900' : '' }}">
                Inventaris (Aslab)
            </a>
            <a href="{{-- route('aslab.peminjaman.index') --}}" class="block px-2 py-2 mt-1 text-sm font-medium rounded-md hover:bg-gray-700 {{-- request()->routeIs('aslab.peminjaman.*') ? 'bg-gray-900' : '' --}}">
                Peminjaman (Aslab)
            </a>
        @endif

        @if(Auth::user()->isMahasiswa())
            <a href="{{-- route('mahasiswa.inventaris.index') --}}" class="block px-2 py-2 mt-1 text-sm font-medium rounded-md hover:bg-gray-700 {{-- request()->routeIs('mahasiswa.inventaris.*') ? 'bg-gray-900' : '' --}}">
                Inventaris (Mhs)
            </a>
            <a href="{{-- route('mahasiswa.peminjaman.history') --}}" class="block px-2 py-2 mt-1 text-sm font-medium rounded-md hover:bg-gray-700 {{-- request()->routeIs('mahasiswa.peminjaman.*') ? 'bg-gray-900' : '' --}}">
                Riwayat Peminjaman
            </a>
        @endif
        {{-- Tambahkan link lain jika perlu --}}
    </nav>
</div>