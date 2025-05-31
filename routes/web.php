<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\BorrowingController;

// Halaman Awal
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('welcome');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- RUTE UNTUK ADMIN DAN ASLAB (MENGGUNAKAN PATH CLASS PENUH) ---
    Route::middleware([\App\Http\Middleware\EnsureUserHasRole::class . ':Admin,Aslab']) // <<---- PERUBAHAN DI SINI
        ->prefix('admin')->name('admin.')->group(function () {
        // Manajemen Inventaris
        Route::get('inventaris', [InventoryController::class, 'index'])->name('inventaris.index');
        Route::get('inventaris/create', [InventoryController::class, 'create'])->name('inventaris.create');
        Route::post('inventaris', [InventoryController::class, 'store'])->name('inventaris.store');
        Route::get('inventaris/{inventaris}/edit', [InventoryController::class, 'edit'])->name('inventaris.edit');
        Route::put('inventaris/{inventaris}', [InventoryController::class, 'update'])->name('inventaris.update');
        Route::delete('inventaris/{inventaris}', [InventoryController::class, 'destroy'])->name('inventaris.destroy');

        // Permintaan Peminjaman
        Route::get('permintaan-peminjaman', [BorrowingController::class, 'adminRequests'])->name('borrowing.requests');
        Route::patch('permintaan-peminjaman/{borrowing}/approve', [BorrowingController::class, 'approve'])->name('borrowing.approve');
        Route::patch('permintaan-peminjaman/{borrowing}/reject', [BorrowingController::class, 'reject'])->name('borrowing.reject');
        Route::patch('permintaan-peminjaman/{borrowing}/return', [BorrowingController::class, 'markReturned'])->name('borrowing.return');

        // Riwayat Semua Peminjaman
        Route::get('riwayat-peminjaman-semua', [BorrowingController::class, 'adminHistoryAll'])->name('borrowing.history.all');
    });

    // --- RUTE UNTUK MAHASISWA (TETAP MENGGUNAKAN ALIAS 'role') ---
    Route::middleware(['role:Mahasiswa']) // <<---- BIARKAN INI MENGGUNAKAN ALIAS
        ->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('inventaris', [InventoryController::class, 'studentIndex'])->name('inventory.index');
        Route::get('inventaris/{inventaris}', [InventoryController::class, 'studentShow'])->name('inventory.show');
        Route::post('inventaris/{inventaris}/pinjam', [BorrowingController::class, 'store'])->name('borrow.request');
        Route::get('riwayat-peminjaman', [BorrowingController::class, 'studentHistory'])->name('borrowing.history');
    });
});