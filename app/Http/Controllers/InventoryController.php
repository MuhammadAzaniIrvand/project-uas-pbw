<?php

namespace App\Http\Controllers;

use App\Models\Inventaris; // Menggunakan model Inventaris
use App\Models\Kategori;   // Asumsi ada model Kategori untuk dropdown
use Illuminate\Http\Request;
// Pastikan nama Form Request Anda adalah StoreInventarisRequest dan UpdateInventarisRequest
// Dan file tersebut ada di app/Http/Requests/
use App\Http\Requests\StoreInventarisRequest;
use App\Http\Requests\UpdateInventarisRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
// use Illuminate\Support\Facades\Storage; // Aktifkan jika ada file upload

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource for Admin/Aslab.
     */
    public function index(Request $request)
    {
        if (!Gate::allows('manage-inventaris')) {
             abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        $query = Inventaris::query()->with('kategori'); // Eager load relasi kategori

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_alat', 'like', '%' . $searchTerm . '%')
                  ->orWhere('nomor_seri', 'like', '%' . $searchTerm . '%')
                  ->orWhere('lokasi', 'like', '%' . $searchTerm . '%')
                  ->orWhere('deskripsi', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('kategori', function ($subQ) use ($searchTerm) {
                      $subQ->where('nama_kategori', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        if ($request->filled('kategori_id_filter')) {
            $query->where('kategori_id', $request->kategori_id_filter);
        }

        if ($request->filled('kondisi_filter') && $request->kondisi_filter !== 'Semua') {
            $query->where('kondisi', $request->kondisi_filter);
        }

        $sortBy = $request->input('sort_by', 'nama_alat');
        $sortDirection = $request->input('direction', 'asc');
        $allowedSorts = ['nama_alat', 'jumlah', 'kondisi', 'lokasi', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
             $query->orderBy($sortBy, $sortDirection);
        } else {
             $query->orderBy('nama_alat', 'asc');
        }

        // Menggunakan nama variabel $items untuk view, tapi data dari model Inventaris
        $items = $query->paginate(10)->appends($request->query());

        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $kondisiOptions = ['Semua', 'Baik', 'Rusak Ringan', 'Rusak Berat', 'Dalam Perbaikan'];

        return view('inventory.index-admin', compact('items', 'kategoris', 'kondisiOptions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Gate::allows('manage-inventaris')) {
            abort(403, 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
        }
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $kondisiOptions = ['Baik', 'Rusak Ringan', 'Rusak Berat', 'Dalam Perbaikan'];
        return view('inventory.create', compact('kategoris', 'kondisiOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInventarisRequest $request) // Menggunakan StoreInventarisRequest
    {
        if (!Gate::allows('manage-inventaris')) {
            abort(403, 'Akses ditolak.');
        }

        $validatedData = $request->validated(); // Data sudah divalidasi

        try {
            // Logika untuk menyimpan data ke model Inventaris
            Inventaris::create($validatedData); // Menggunakan model Inventaris

            // REDIRECT KE HALAMAN INDEX DENGAN PESAN SUKSES
            return redirect()->route('admin.inventaris.index')
                             ->with('success', 'Inventaris berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan inventaris baru: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            return redirect()->back()
                             ->withInput() // Kembalikan input sebelumnya ke form
                             ->with('error', 'Terjadi kesalahan internal saat menyimpan data. Silakan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventaris $inventaris) // Menggunakan model Inventaris
    {
        if (!Gate::allows('manage-inventaris')) {
            abort(403, 'Akses ditolak.');
        }
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        $kondisiOptions = ['Baik', 'Rusak Ringan', 'Rusak Berat', 'Dalam Perbaikan'];
        // Mengirim variabel $inventaris ke view
        return view('inventory.edit', compact('inventaris', 'kategoris', 'kondisiOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInventarisRequest $request, Inventaris $inventaris) // Menggunakan UpdateInventarisRequest dan model Inventaris
    {
        if (!Gate::allows('manage-inventaris')) {
            abort(403, 'Akses ditolak.');
        }

        $validatedData = $request->validated();

        try {
            $inventaris->update($validatedData);

            return redirect()->route('admin.inventaris.index')
                             ->with('success', 'Inventaris berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error saat mengupdate inventaris (ID: ' . $inventaris->id . '): ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            return redirect()->back()
                             ->withInput()
                             ->with('error', 'Terjadi kesalahan internal saat memperbarui data. Silakan coba lagi atau hubungi administrator.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventaris $inventaris) // Menggunakan model Inventaris
    {
        if (!Gate::allows('manage-inventaris')) {
            abort(403, 'Akses ditolak.');
        }

        try {
            // Pastikan relasi 'peminjaman' ada di model Inventaris
            if ($inventaris->peminjaman()->whereIn('status', ['Dipinjam', 'Terlambat', 'Menunggu Persetujuan'])->exists()) {
                 return redirect()->route('admin.inventaris.index')
                                  ->with('error', 'Inventaris tidak bisa dihapus karena sedang ada dalam proses peminjaman.');
            }

            $inventaris->delete();

            return redirect()->route('admin.inventaris.index')
                             ->with('success', 'Inventaris berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error saat menghapus inventaris (ID: ' . $inventaris->id . '): ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            return redirect()->route('admin.inventaris.index')
                             ->with('error', 'Terjadi kesalahan internal saat menghapus data. Silakan coba lagi atau hubungi administrator.');
        }
    }

    // --- METHOD UNTUK MAHASISWA ---

    public function studentIndex(Request $request)
    {
        $query = Inventaris::where('jumlah', '>', 0)->with('kategori'); // Menggunakan model Inventaris

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama_alat', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('kategori', function ($subQ) use ($searchTerm) {
                      $subQ->where('nama_kategori', 'like', '%' . $searchTerm . '%');
                  });
            });
        }
        if ($request->filled('kategori_id_filter')) {
            $query->where('kategori_id', $request->kategori_id_filter);
        }

        // Menggunakan nama variabel $items untuk view
        $items = $query->latest('nama_alat')->paginate(9)->appends($request->query());
        $kategoris = Kategori::orderBy('nama_kategori')->get();

        return view('inventory.index-student', compact('items', 'kategoris'));
    }

    public function studentShow(Inventaris $inventaris) // Menggunakan model Inventaris
    {
        if ($inventaris->jumlah <= 0 && Auth::check() && Auth::user()->role === 'Mahasiswa') {
            return redirect()->route('mahasiswa.inventory.index')->with('error', 'Stok item ini sedang habis atau item tidak tersedia.');
        }
        $inventaris->load('kategori');
        // Mengirim variabel $inventaris ke view
        return view('inventory.show-student', compact('inventaris'));
    }
}