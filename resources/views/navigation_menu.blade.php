<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate; // Kita akan gunakan Gate

class StoreItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Menggunakan Gate yang sudah kita definisikan di AppServiceProvider
        return Gate::allows('manage-inventaris');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'name' menggantikan 'nama_alat'
            'name' => 'required|string|max:255|unique:items,name', // Pastikan tabel 'items' dan kolom 'name'
            // 'quantity' menggantikan 'jumlah'
            'quantity' => 'required|integer|min:0', // Stok bisa 0
            // 'category' adalah string, bukan foreign key 'kategori_id'
            'category' => 'nullable|string|max:100',
            // 'location' tetap
            'location' => 'nullable|string|max:255',
            // 'description' tetap
            'description' => 'nullable|string',
            // 'image_path' adalah untuk file gambar (opsional)
            // 'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

            // Field yang tidak kita gunakan dari request lama (bisa dihapus atau disesuaikan jika memang ada di model Item Anda):
            // 'kondisi' => 'required|string|in:Baik,Rusak Ringan,Rusak Berat,Dalam Perbaikan', // Hapus jika tidak ada kolom 'kondisi' di tabel 'items'
            // 'nomor_seri' => 'nullable|string|max:255|unique:items,nomor_seri', // Hapus jika tidak ada 'nomor_seri'
            // 'tanggal_pengadaan' => 'nullable|date_format:Y-m-d', // Hapus jika tidak ada 'tanggal_pengadaan'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama item wajib diisi.',
            'name.unique' => 'Nama item sudah ada.',
            'quantity.required' => 'Jumlah item wajib diisi.',
            'quantity.integer' => 'Jumlah item harus berupa angka.',
            'quantity.min' => 'Jumlah item minimal 0.',
            // Tambahkan pesan kustom lainnya jika perlu
        ];
    }
}