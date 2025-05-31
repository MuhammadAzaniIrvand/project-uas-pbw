<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController; // Pastikan nama controller ini benar
use App\Http\Controllers\BorrowingController; // Pastikan nama controller ini benar

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Halaman Awal
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard'); // Arahkan ke dashboard jika sudah login
    }
    return redirect()->route('login'); // Arahkan ke login jika belum
})->name('welcome');


// Rute yang memerlukan Autentikasi (sudah login)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified', // Hapus jika Anda tidak menggunakan verifikasi email
])->group(function () {

    // Dashboard (Akan dihandle oleh DashboardController untuk role yang berbeda)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- RUTE UNTUK ADMIN DAN ASLAB ---
    // Menggunakan path class penuh untuk middleware
    Route::middleware([\App\Http\Middleware\EnsureUserHasRole::class . ':Admin,Aslab'])
        ->prefix('admin')->name('admin.')->group(function () {
        // Manajemen Inventaris (CRUD oleh Admin/Aslab)
        // Pastikan parameter model adalah {inventaris} jika model Anda Inventaris
        Route::get('inventaris', [InventoryController::class, 'index'])->name('inventaris.index');
        Route::get('inventaris/create', [InventoryController::class, 'create'])->name('inventaris.create');
        Route::post('inventaris', [InventoryController::class, 'store'])->name('inventaris.store');
        Route::get('inventaris/{inventaris}/edit', [InventoryController::class, 'edit'])->name('inventaris.edit');
        Route::put('inventaris/{inventaris}', [InventoryController::class, 'update'])->name('inventaris.update');
        Route::delete('inventaris/{inventaris}', [InventoryController::class, 'destroy'])->name('inventaris.destroy');

        // Rute untuk Permintaan Peminjaman (Admin/Aslab)
        Route::get('permintaan-peminjaman', [BorrowingController::class, 'adminRequests'])->name('borrowing.requests');
        Route::patch('permintaan-peminjaman/{borrowing}/approve', [BorrowingController::class, 'approve'])->name('borrowing.approve');
        Route::patch('permintaan-peminjaman/{borrowing}/reject', [BorrowingController::class, 'reject'])->name('borrowing.reject');
        Route::patch('permintaan-peminjaman/{borrowing}/return', [BorrowingController::class, 'markReturned'])->name('borrowing.return');

        // Rute untuk Riwayat Semua Peminjaman (Admin/Aslab)
        Route::get('riwayat-peminjaman-semua', [BorrowingController::class, 'adminHistoryAll'])->name('borrowing.history.all');
        Route::get('peminjaman/{borrowing}', [BorrowingController::class, 'show'])->name('borrowing.show'); // Detail peminjaman untuk admin
    });

    // --- RUTE UNTUK MAHASISWA ---
    // Menggunakan path class penuh untuk middleware
    Route::middleware([\App\Http\Middleware\EnsureUserHasRole::class . ':Mahasiswa']) // <<---- PERUBAHAN DI SINI
        ->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        // Lihat Inventaris dan Detail Item (Mahasiswa)
        Route::get('inventaris', [InventoryController::class, 'studentIndex'])->name('inventory.index');
        Route::get('inventaris/{inventaris}', [InventoryController::class, 'studentShow'])->name('inventory.show');

        // Rute untuk Membuat Permintaan Peminjaman (Mahasiswa)
        Route::post('inventaris/{inventaris}/pinjam', [BorrowingController::class, 'store'])->name('borrow.request');

        // Rute untuk Riwayat Peminjaman Mahasiswa
        Route::get('riwayat-peminjaman', [BorrowingController::class, 'studentHistory'])->name('borrowing.history');
        Route::get('peminjaman/{borrowing}', [BorrowingController::class, 'show'])->name('borrowing.show'); // Detail peminjaman untuk mahasiswa
    });

});

// Catatan: Rute login, register, logout, forgot-password, dll.
// sudah di-handle oleh Jetstream/Fortify secara otomatis.