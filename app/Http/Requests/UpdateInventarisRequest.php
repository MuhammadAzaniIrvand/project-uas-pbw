<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule; // Diperlukan untuk rule unique->ignore()

class UpdateInventarisRequest extends FormRequest
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
        // Mendapatkan ID inventaris dari parameter route.
        // Pastikan nama parameter di definisi route Anda adalah 'inventaris'.
        // Contoh: Route::put('/admin/inventaris/{inventaris}', [InventoryController::class, 'update']);
        $inventarisId = $this->route('inventaris')->id;

        return [
            'nama_alat' => [
                'sometimes', // Hanya validasi jika field ini ada di request
                'required',  // Jika ada, maka wajib diisi
                'string',
                'max:255',
                Rule::unique('inventaris', 'nama_alat')->ignore($inventarisId) // Abaikan ID saat ini untuk cek unique
            ],
            'kategori_id' => 'sometimes|nullable|integer|exists:kategoris,id',
            'kondisi' => 'sometimes|required|string|in:Baik,Rusak Ringan,Rusak Berat,Dalam Perbaikan',
            'jumlah' => 'sometimes|required|integer|min:0', // Jumlah bisa 0 saat update
            'lokasi' => 'sometimes|nullable|string|max:255',
            'deskripsi' => 'sometimes|nullable|string',
            'nomor_seri' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                Rule::unique('inventaris', 'nomor_seri')->ignore($inventarisId)
            ],
            'tanggal_pengadaan' => 'sometimes|nullable|date_format:Y-m-d',
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
            'nama_alat.required' => 'Nama alat wajib diisi jika ingin diubah.',
            'nama_alat.unique' => 'Nama alat sudah terdaftar.',
            'nama_alat.max' => 'Nama alat tidak boleh lebih dari 255 karakter.',
            'kategori_id.integer' => 'Kategori harus berupa pilihan yang valid.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid.',
            'kondisi.required' => 'Kondisi alat wajib dipilih jika ingin diubah.',
            'kondisi.in' => 'Kondisi alat tidak valid.',
            'jumlah.required' => 'Jumlah alat wajib diisi jika ingin diubah.',
            'jumlah.integer' => 'Jumlah alat harus berupa angka.',
            'jumlah.min' => 'Jumlah alat minimal 0.',
            'nomor_seri.unique' => 'Nomor seri sudah terdaftar.',
            'nomor_seri.max' => 'Nomor seri tidak boleh lebih dari 255 karakter.',
            'tanggal_pengadaan.date_format' => 'Format tanggal pengadaan harus YYYY-MM-DD.',
        ];
    }
}