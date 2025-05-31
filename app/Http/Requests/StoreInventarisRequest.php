<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreInventarisRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Menggunakan Gate yang sudah didefinisikan untuk otorisasi
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
            'nama_alat' => 'required|string|max:255|unique:inventaris,nama_alat', // unik di tabel 'inventaris'
            'kategori_id' => 'nullable|integer|exists:kategoris,id', // Pastikan tabel 'kategoris' ada
            'kondisi' => 'required|string|in:Baik,Rusak Ringan,Rusak Berat,Dalam Perbaikan',
            'jumlah' => 'required|integer|min:1', // Jumlah minimal 1 saat membuat baru
            'lokasi' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'nomor_seri' => 'nullable|string|max:255|unique:inventaris,nomor_seri', // unik di tabel 'inventaris'
            'tanggal_pengadaan' => 'nullable|date_format:Y-m-d', // Format YYYY-MM-DD
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
            'nama_alat.required' => 'Nama alat wajib diisi.',
            'nama_alat.unique' => 'Nama alat sudah terdaftar.',
            'nama_alat.max' => 'Nama alat tidak boleh lebih dari 255 karakter.',
            'kategori_id.integer' => 'Kategori harus berupa pilihan yang valid.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
            'kondisi.required' => 'Kondisi alat wajib dipilih.',
            'kondisi.in' => 'Kondisi alat tidak valid.',
            'jumlah.required' => 'Jumlah alat wajib diisi.',
            'jumlah.integer' => 'Jumlah alat harus berupa angka.',
            'jumlah.min' => 'Jumlah alat minimal 1.',
            'nomor_seri.unique' => 'Nomor seri sudah terdaftar.',
            'nomor_seri.max' => 'Nomor seri tidak boleh lebih dari 255 karakter.',
            'tanggal_pengadaan.date_format' => 'Format tanggal pengadaan harus YYYY-MM-DD.',
        ];
    }
}