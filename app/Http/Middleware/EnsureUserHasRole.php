<?php

namespace App\Http\Middleware; // Pastikan namespace ini benar

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response; // Atau use Illuminate\Http\Response;

class EnsureUserHasRole // Nama class tetap sama
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles // <<<< PERUBAHAN KRUSIAL DI SINI: Variadic argument
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response // <<<< PERUBAHAN DI SINI
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        // Pastikan user memiliki properti 'role' dan tidak null
        if (!$user || !isset($user->role)) {
            // Atau log error dan redirect/abort
            return redirect()->route('dashboard')->with('error', 'Informasi role pengguna tidak valid.');
        }

        // Cek apakah role user ada di dalam array $roles yang diizinkan
        // $roles sekarang adalah sebuah array, contoh: ['Admin', 'Aslab'] atau ['Mahasiswa']
        if (!in_array($user->role, $roles)) { // <<<< PERUBAHAN KRUSIAL DI SINI: Menggunakan in_array
            return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        return $next($request);
    }
}