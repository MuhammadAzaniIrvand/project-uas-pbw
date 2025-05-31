<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Inventaris; // Pastikan ini adalah model inventaris Anda yang benar
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule; // Tidak terpakai di versi ini, tapi bisa berguna jika ada validasi status lebih kompleks
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BorrowingController extends Controller
{
    /**
     * Menampilkan riwayat peminjaman untuk mahasiswa yang sedang login.
     */
    public function studentHistory(Request $request)
    {
        $user = Auth::user();
        $query = Peminjaman::where('user_id', $user->id)
                                ->with(['inventaris.kategori', 'petugas'])
                                ->latest('tanggal_pinjam');

        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }

        $borrowings = $query->paginate(10)->appends($request->query());
        // Kirim $statuses untuk filter dropdown di view
        $statuses = ['Semua', 'Menunggu Persetujuan', 'Dipinjam', 'Dikembalikan', 'Terlambat', 'Ditolak'];

        return view('borrowing.history-student', compact('borrowings', 'user', 'statuses'));
    }

    /**
     * Menampilkan daftar permintaan peminjaman yang perlu ditindaklanjuti oleh Admin/Aslab.
     */
    public function adminRequests(Request $request)
    {
        if (!Gate::allows('manage-inventaris')) { // Pertimbangkan Gate 'manage-peminjaman'
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        $query = Peminjaman::with(['user', 'inventaris.kategori'])
                            ->where('status', 'Menunggu Persetujuan');

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
        if (!Gate::allows('manage-inventaris')) { // Pertimbangkan Gate 'manage-peminjaman'
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        $query = Peminjaman::with(['user', 'inventaris.kategori', 'petugas']);

        if ($request->filled('status_peminjaman') && $request->status_peminjaman != 'Semua') {
            $query->where('status', $request->status_peminjaman);
        }

        if ($request->filled('search_peminjaman')) {
            $searchTerm = $request->search_peminjaman;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('inventaris', fn($sq) => $sq->where('nama_alat', 'like', "%{$searchTerm}%"))
                  ->orWhereHas('user', fn($sq) => $sq->where('name', 'like', "%{$searchTerm}%")->orWhere('npm', 'like', "%{$searchTerm}%"));
            });
        }

        $borrowings = $query->latest('tanggal_pinjam')->paginate(10)->appends($request->query());
        $statuses = ['Semua', 'Menunggu Persetujuan', 'Dipinjam', 'Dikembalikan', 'Terlambat', 'Ditolak'];

        return view('borrowing.history-all-admin', compact('borrowings', 'statuses'));
    }

    /**
     * Menyimpan permintaan peminjaman baru dari mahasiswa.
     */
    public function store(Request $request, Inventaris $inventaris) // Route Model Binding untuk $inventaris
    {
        if (Auth::user()->role !== 'Mahasiswa') {
            return redirect()->back()->with('error_pinjam', 'Hanya mahasiswa yang dapat mengajukan peminjaman.');
        }

        $validatedData = $request->validate([
            'jumlah_pinjam' => ['required', 'integer', 'min:1',
                function ($attribute, $value, $fail) use ($inventaris) {
                    $inventaris->refresh(); // Ambil data stok terbaru
                    if ($inventaris->jumlah < $value) {
                        $fail('Jumlah pinjam (' . $value . ') melebihi stok yang tersedia (Stok: ' . $inventaris->jumlah . ').');
                    }
                },
            ],
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:today',
            'tujuan_peminjaman' => 'required|string|max:1000',
        ]);

        Peminjaman::create([
            'user_id' => Auth::id(),
            'inventaris_id' => $inventaris->id,
            'jumlah_pinjam' => $validatedData['jumlah_pinjam'],
            'tanggal_pinjam' => now(),
            'tanggal_kembali_rencana' => $validatedData['tanggal_kembali_rencana'],
            'tujuan_peminjaman' => $validatedData['tujuan_peminjaman'],
            'status' => 'Menunggu Persetujuan',
            'catatan_petugas' => null, // Jika Anda menggunakan field ini
        ]);

        return redirect()->route('mahasiswa.borrowing.history')
                         ->with('success', 'Permintaan peminjaman berhasil dikirim dan menunggu persetujuan.');
    }

    /**
     * Menampilkan detail peminjaman (jika diperlukan).
     */
    public function show(Peminjaman $borrowing) // Menggunakan $borrowing
    {
        $user = Auth::user();
        if ($user->role === 'Mahasiswa' && $borrowing->user_id !== $user->id) {
            if (!Gate::allows('manage-inventaris')) {
                abort(403, 'Anda tidak memiliki izin untuk melihat detail peminjaman ini.');
            }
        }

        $borrowing->load(['user', 'inventaris.kategori', 'petugas']);
        return view('borrowing.show-detail', compact('borrowing'));
    }

    /**
     * Menyetujui permintaan peminjaman (oleh Admin/Aslab).
     */
    public function approve(Peminjaman $borrowing) // Menggunakan $borrowing
    {
        if (!Gate::allows('manage-inventaris')) {
            return redirect()->route('admin.borrowing.requests')->with('error', 'Akses ditolak.');
        }

        if ($borrowing->status !== 'Menunggu Persetujuan') {
            Log::warning("Gagal approve peminjaman ID {$borrowing->id}. Status saat ini: '{$borrowing->status}', diharapkan: 'Menunggu Persetujuan'.");
            return redirect()->route('admin.borrowing.requests')->with('error', 'Peminjaman ini tidak bisa disetujui (status bukan Menunggu Persetujuan).');
        }

        $inventaris = $borrowing->inventaris;

        DB::beginTransaction();
        try {
            $inventaris->refresh(); // Ambil data stok terbaru
            if ($inventaris->jumlah < $borrowing->jumlah_pinjam) {
                DB::rollBack();
                return redirect()->route('admin.borrowing.requests')->with('error', 'Gagal menyetujui: Stok inventaris tidak mencukupi. Sisa stok: ' . $inventaris->jumlah);
            }

            $inventaris->decrement('jumlah', $borrowing->jumlah_pinjam);

            $borrowing->status = 'Dipinjam';
            $borrowing->petugas_id = Auth::id();
            $borrowing->save();

            DB::commit();

            // Redirect ke halaman permintaan lagi, item yang disetujui akan hilang dari daftar
            return redirect()->route('admin.borrowing.requests')->with('success', 'Permintaan peminjaman berhasil disetujui.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menyetujui peminjaman ID ' . $borrowing->id . ': ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            return redirect()->route('admin.borrowing.requests')->with('error', 'Terjadi kesalahan internal saat menyetujui peminjaman.');
        }
    }

    /**
     * Menolak permintaan peminjaman (oleh Admin/Aslab).
     */
    public function reject(Request $request, Peminjaman $borrowing) // Menggunakan $borrowing
    {
        if (!Gate::allows('manage-inventaris')) {
            return redirect()->route('admin.borrowing.requests')->with('error', 'Akses ditolak.');
        }

        if ($borrowing->status !== 'Menunggu Persetujuan') {
            return redirect()->route('admin.borrowing.requests')->with('error', 'Peminjaman ini tidak bisa ditolak (status bukan Menunggu Persetujuan).');
        }

        $validatedData = $request->validate(['catatan_petugas' => 'nullable|string|max:1000']); // Asumsi name input di modal adalah 'catatan_petugas'

        $borrowing->status = 'Ditolak';
        $borrowing->petugas_id = Auth::id();
        $borrowing->catatan_petugas = $validatedData['catatan_petugas'] ?? null; // Sesuaikan jika nama kolom di model adalah 'catatan_pengembalian'
        $borrowing->save();

        return redirect()->route('admin.borrowing.requests')->with('success', 'Permintaan peminjaman berhasil ditolak.');
    }

    /**
     * Menandai barang sudah dikembalikan (oleh Admin/Aslab).
     */
    public function markReturned(Request $request, Peminjaman $borrowing) // Menggunakan $borrowing
    {
        if (!Gate::allows('manage-inventaris')) {
             return redirect()->route('admin.borrowing.history.all')->with('error', 'Akses ditolak.');
        }

        if (!in_array($borrowing->status, ['Dipinjam', 'Terlambat'])) {
            return redirect()->route('admin.borrowing.history.all')->with('error', 'Peminjaman tidak dalam status yang bisa dikembalikan.');
        }

        $validatedData = $request->validate(['catatan_petugas' => 'nullable|string|max:1000']); // Asumsi name input di modal adalah 'catatan_petugas'

        $inventaris = $borrowing->inventaris;

        DB::beginTransaction();
        try {
            $inventaris->increment('jumlah', $borrowing->jumlah_pinjam);

            $borrowing->tanggal_kembali_aktual = now();
            $borrowing->petugas_id = Auth::id();
            // Sesuaikan nama kolom ini jika di model Anda adalah 'catatan_pengembalian'
            $borrowing->catatan_petugas = $validatedData['catatan_petugas'] ?? $borrowing->catatan_petugas;


            if ($borrowing->tanggal_kembali_rencana && now()->greaterThan($borrowing->tanggal_kembali_rencana->endOfDay())) {
                $borrowing->status = 'Terlambat';
            } else {
                $borrowing->status = 'Dikembalikan';
            }
            $borrowing->save();

            DB::commit();
            return redirect()->route('admin.borrowing.history.all')->with('success', 'Barang telah ditandai sebagai dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat menandai pengembalian untuk ID ' . $borrowing->id . ': ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            return redirect()->route('admin.borrowing.history.all')->with('error', 'Terjadi kesalahan internal saat menandai pengembalian.');
        }
    }
}