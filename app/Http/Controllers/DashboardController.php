<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Jika perlu menghitung total user
use App\Models\Inventaris; // Jika perlu menghitung total inventaris
use App\Models\Peminjaman; // Jika perlu menghitung data peminjaman

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = ['user' => $user]; // Data dasar yang selalu dikirim

        if (!$user) {
            return redirect()->route('login'); // Pengaman jika user tidak terautentikasi
        }

        if (in_array($user->role, ['Admin', 'Aslab'])) {
            // Data untuk Dashboard Admin/Aslab
            $data['totalUsers'] = User::count();
            $data['totalInventaris'] = Inventaris::count();
            $data['totalLaboratorium'] = 5; // Contoh statis, atau ambil dari DB jika ada tabel laboratorium

            $data['peminjamanAktif'] = Peminjaman::where('status', 'Dipinjam')->count();
            $data['peminjamanTerlambat'] = Peminjaman::where('status', 'Terlambat')->count(); // Atau overdue
            $data['permintaanPending'] = Peminjaman::where('status', 'Menunggu Persetujuan')->count();
            $data['totalPeminjamanDisetujuiHariIni'] = Peminjaman::where('status', 'Dipinjam')
                                                                ->whereDate('updated_at', today()) // Asumsi 'updated_at' diupdate saat approve
                                                                ->count();

            // Contoh data untuk chart (7 hari terakhir)
            // Anda perlu logika lebih lanjut untuk mengagregasi data ini per hari
            $data['aktivitasPeminjaman7Hari'] = $this->getBorrowingActivityLast7Days();
            $data['aktivitasBookingLab7Hari'] = $this->getLabBookingActivityLast7Days(); // Jika ada fitur booking lab

        } elseif ($user->role === 'Mahasiswa') {
            // Data untuk Dashboard Mahasiswa
            $data['peminjamanAktifSaya'] = Peminjaman::where('user_id', $user->id)
                                                    ->where('status', 'Dipinjam')
                                                    ->count();
            $data['permintaanPendingSaya'] = Peminjaman::where('user_id', $user->id)
                                                      ->where('status', 'Menunggu Persetujuan')
                                                      ->count();
            $data['totalPeminjamanDisetujui'] = Peminjaman::where('user_id', $user->id)
                                                        ->where('status', 'Dipinjam') // atau 'Dikembalikan' & 'Terlambat' jika mau total yg pernah disetujui
                                                        ->count();
            // Data lain yang relevan untuk mahasiswa
        }

        return view('dashboard', $data);
    }

    // Contoh helper method untuk data chart (PERLU PENYESUAIAN LOGIKA)
    private function getBorrowingActivityLast7Days()
    {
        // Ini hanya placeholder, Anda perlu query yang benar untuk mengagregasi per hari
        // Contoh: SELECT DATE(created_at) as date, COUNT(*) as count FROM peminjamans WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY DATE(created_at)
        $activity = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $count = Peminjaman::whereDate('tanggal_pinjam', $date)->count(); // Atau 'created_at' saat permintaan dibuat
            $activity[] = [
                'date' => $date->format('d M'), // Format tanggal untuk label chart
                'count' => $count,
            ];
        }
        return $activity;
    }

    private function getLabBookingActivityLast7Days()
    {
        // Placeholder jika ada fitur booking lab
        $activity = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);
            $activity[] = [
                'date' => $date->format('d M'),
                'count' => rand(0, 5), // Data acak untuk contoh
            ];
        }
        return $activity;
    }
}