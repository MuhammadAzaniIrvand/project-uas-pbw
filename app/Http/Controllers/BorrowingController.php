<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Inventaris; // Pastikan ini nama model inventaris Anda
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB; // Untuk transaksi database

class BorrowingController extends Controller
{
    /**
     * Menampilkan riwayat peminjaman untuk mahasiswa yang sedang login.
     */
    public function studentHistory()
    {
        $user = Auth::user();
        $borrowings = Peminjaman::where('user_id', $user->id)
                                ->with(['inventaris', 'petugas']) // Eager load
                                ->latest('tanggal_pinjam')      // Urutkan terbaru dulu
                                ->paginate(10);                 // Paginasi

        return view('borrowing.history-student', compact('borrowings', 'user'));
    }

    /**
     * Menampilkan daftar permintaan peminjaman yang perlu ditindaklanjuti oleh Admin/Aslab.
     * Status: 'Menunggu Persetujuan'
     */
    public function adminRequests(Request $request)
    {
        if (!Gate::allows('manage-inventaris')) { // Ganti dengan Gate 'manage-peminjaman' jika ada
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        $query = Peminjaman::with(['user', 'inventaris'])
                            ->where('status', 'Menunggu Persetujuan');

        // Anda bisa menambahkan fitur search di sini jika diperlukan
        if ($request->filled('search_requests')) {
            $searchTerm = $request->search_requests;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('inventaris', fn($sq) => $sq->where('nama_alat', 'like', "%{$searchTerm}%"))
                  ->orWhereHas('user', fn($sq) => $sq->where('name', 'like', "%{$searchTerm}%")->orWhere('npm', 'like', "%{$searchTerm}%"));
            });
        }

        $requests = $query->latest('created_at')->paginate(10)->appends($request->query());

        return view('borrowing.requests-admin', compact('requests'));
    }

    /**
     * Menampilkan semua riwayat peminjaman untuk Admin/Aslab dengan filter.
     */
    public function adminHistoryAll(Request $request)
    {
        if (!Gate::allows('manage-inventaris')) { // Ganti dengan Gate 'manage-peminjaman' jika ada
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        $query = Peminjaman::with(['user', 'inventaris', 'petugas']);

        // Filter berdasarkan status
        if ($request->filled('status_peminjaman') && $request->status_peminjaman != 'Semua') {
            $query->where('status', $request->status_peminjaman);
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search_peminjaman')) {
            $searchTerm = $request->search_peminjaman;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('inventaris', fn($sq) => $sq->where('nama_alat', 'like', "%{$searchTerm}%"))
                  ->orWhereHas('user', fn($sq) => $sq->where('name', 'like', "%{$searchTerm}%")->orWhere('npm', 'like', "%{$searchTerm}%"));
            });
        }

        $borrowings = $query->latest('tanggal_pinjam')->paginate(10)->appends($request->query());
        $statuses = ['Semua', 'Menunggu Persetujuan', 'Dipinjam', 'Dikembalikan', 'Terlambat', 'Ditolak']; // Untuk dropdown filter

        return view('borrowing.history-all-admin', compact('borrowings', 'statuses'));
    }

    /**
     * Menyimpan permintaan peminjaman baru dari mahasiswa.
     */
    public function store(Request $request, Inventaris $inventaris) // Route Model Binding untuk $inventaris
    {
        if (Auth::user()->role !== 'Mahasiswa') {
            return redirect()->back()->with('error', 'Hanya mahasiswa yang dapat mengajukan peminjaman.');
        }

        $validatedData = $request->validate([
            'jumlah_pinjam' => 'required|integer|min:1',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:today',
            'tujuan_peminjaman' => 'required|string|max:1000', // Dibuat wajib
        ]);

        // Cek ketersediaan stok
        if ($inventaris->jumlah < $validatedData['jumlah_pinjam']) {
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Stok inventaris tidak mencukupi. Sisa stok: ' . $inventaris->jumlah);
        }

        Peminjaman::create([
            'user_id' => Auth::id(),
            'inventaris_id' => $inventaris->id,
            'jumlah_pinjam' => $validatedData['jumlah_pinjam'],
            'tanggal_pinjam' => now(), // Otomatis saat ini
            'tanggal_kembali_rencana' => $validatedData['tanggal_kembali_rencana'],
            'tujuan_peminjaman' => $validatedData['tujuan_peminjaman'],
            'status' => 'Menunggu Persetujuan',
        ]);

        return redirect()->route('mahasiswa.borrowing.history')
                         ->with('success', 'Permintaan peminjaman berhasil dikirim dan menunggu persetujuan.');
    }

    /**
     * Menampilkan detail peminjaman (jika diperlukan).
     */
    public function show(Peminjaman $peminjaman) // Route Model Binding
    {
        $user = Auth::user();
        // Otorisasi: Mahasiswa hanya boleh lihat detail peminjamannya sendiri, Admin/Aslab boleh semua
        if ($user->role === 'Mahasiswa' && $peminjaman->user_id !== $user->id) {
            if (!Gate::allows('manage-inventaris')) { // Ganti dengan Gate 'view-any-peminjaman' jika ada
                abort(403, 'Anda tidak memiliki izin untuk melihat detail peminjaman ini.');
            }
        }
        // Alternatif: $this->authorize('view', $peminjaman); // Jika menggunakan Policy

        $peminjaman->load(['user', 'inventaris', 'petugas']);
        return view('borrowing.show-detail', compact('peminjaman')); // Anda perlu membuat view ini
    }

    /**
     * Menyetujui permintaan peminjaman (oleh Admin/Aslab).
     */
    public function approve(Peminjaman $peminjaman) // Route Model Binding
    {
        if (!Gate::allows('manage-inventaris')) { // Ganti dengan Gate 'manage-peminjaman'
            return redirect()->route('admin.borrowing.requests')->with('error', 'Akses ditolak.');
        }

        if ($peminjaman->status !== 'Menunggu Persetujuan') {
            return redirect()->route('admin.borrowing.requests')->with('error', 'Peminjaman ini tidak bisa disetujui (status bukan Menunggu Persetujuan).');
        }

        $inventaris = $peminjaman->inventaris;

        DB::beginTransaction();
        try {
            if ($inventaris->jumlah < $peminjaman->jumlah_pinjam) {
                DB::rollBack();
                return redirect()->route('admin.borrowing.requests')->with('error', 'Gagal menyetujui: Stok inventaris tidak mencukupi. Sisa stok: ' . $inventaris->jumlah);
            }

            $inventaris->decrement('jumlah', $peminjaman->jumlah_pinjam); // Kurangi stok

            $peminjaman->status = 'Dipinjam';
            $peminjaman->petugas_id = Auth::id(); // Catat petugas yang memproses
            // $peminjaman->tanggal_disetujui = now(); // Opsional: tambahkan kolom ini di model & migrasi jika perlu
            $peminjaman->save();

            DB::commit();
            // TODO: Kirim notifikasi ke mahasiswa jika perlu

            return redirect()->route('admin.borrowing.requests')->with('success', 'Permintaan peminjaman berhasil disetujui.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error('Error approving borrowing: ' . $e->getMessage()); // Sebaiknya log error
            return redirect()->route('admin.borrowing.requests')->with('error', 'Terjadi kesalahan saat menyetujui peminjaman.');
        }
    }

    /**
     * Menolak permintaan peminjaman (oleh Admin/Aslab).
     */
    public function reject(Request $request, Peminjaman $peminjaman) // Route Model Binding
    {
        if (!Gate::allows('manage-inventaris')) { // Ganti dengan Gate 'manage-peminjaman'
            return redirect()->route('admin.borrowing.requests')->with('error', 'Akses ditolak.');
        }

        if ($peminjaman->status !== 'Menunggu Persetujuan') {
            return redirect()->route('admin.borrowing.requests')->with('error', 'Peminjaman ini tidak bisa ditolak (status bukan Menunggu Persetujuan).');
        }

        // Asumsi nama field di form adalah 'catatan_petugas'
        $validatedData = $request->validate(['catatan_petugas' => 'nullable|string|max:1000']);

        $peminjaman->status = 'Ditolak';
        $peminjaman->petugas_id = Auth::id();
        $peminjaman->catatan_petugas = $validatedData['catatan_petugas'] ?? null; // Simpan alasan penolakan
        $peminjaman->save();

        // TODO: Kirim notifikasi ke mahasiswa jika perlu

        return redirect()->route('admin.borrowing.requests')->with('success', 'Permintaan peminjaman berhasil ditolak.');
    }

    /**
     * Menandai barang sudah dikembalikan (oleh Admin/Aslab).
     */
    public function markReturned(Request $request, Peminjaman $peminjaman) // Route Model Binding
    {
        if (!Gate::allows('manage-inventaris')) { // Ganti dengan Gate 'manage-peminjaman'
             return redirect()->route('admin.borrowing.history.all')->with('error', 'Akses ditolak.');
        }

        if (!in_array($peminjaman->status, ['Dipinjam', 'Terlambat'])) {
            return redirect()->route('admin.borrowing.history.all')->with('error', 'Peminjaman tidak dalam status yang bisa dikembalikan.');
        }

        // Asumsi nama field di form adalah 'catatan_petugas'
        $validatedData = $request->validate(['catatan_petugas' => 'nullable|string|max:1000']);

        $inventaris = $peminjaman->inventaris;

        DB::beginTransaction();
        try {
            $inventaris->increment('jumlah', $peminjaman->jumlah_pinjam); // Tambah stok kembali

            $peminjaman->tanggal_kembali_aktual = now();
            $peminjaman->petugas_id = Auth::id();
            $peminjaman->catatan_petugas = $validatedData['catatan_petugas'] ?? $peminjaman->catatan_petugas; // Update atau tambahkan catatan

            // Cek apakah terlambat
            if ($peminjaman->tanggal_kembali_rencana && now()->greaterThan($peminjaman->tanggal_kembali_rencana->endOfDay())) {
                $peminjaman->status = 'Terlambat';
            } else {
                $peminjaman->status = 'Dikembalikan';
            }
            $peminjaman->save();

            DB::commit();
            return redirect()->route('admin.borrowing.history.all')->with('success', 'Barang telah ditandai sebagai dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error('Error marking returned: ' . $e->getMessage()); // Sebaiknya log error
            return redirect()->route('admin.borrowing.history.all')->with('error', 'Terjadi kesalahan saat menandai pengembalian.');
        }
    }
}