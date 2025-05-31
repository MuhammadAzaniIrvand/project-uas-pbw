<?php

namespace Database\Seeders;

use App\Models\User; // Pastikan User model di-import
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Bagian ini dari Jetstream, untuk membuat satu user default
        // dan tim personalnya. Anda bisa mengomentarinya jika UserRoleSeeder Anda
        // sudah membuat user yang Anda butuhkan (misalnya, user admin).
        // Atau, modifikasi user ini agar sesuai dengan kebutuhan Anda.
        // Jika UserRoleSeeder membuat user 'Test User' juga, maka baris ini jadi duplikat.
        /*
        User::factory()->withPersonalTeam()->create([
            'name' => 'Test User', // Mungkin Anda ingin menggantinya dengan Admin User
            'email' => 'admin@example.com', // Email Admin
            'npm' => 'ADMIN001', // NPM untuk Admin (jika perlu)
            'role' => 'Admin', // Role Admin
            // 'password' => bcrypt('password'), // Jika tidak dihandle factory
        ]);
        */

        // Panggil seeder-seeder dari proyek lama Anda.
        // Pastikan semua class seeder ini (UserRoleSeeder, KategoriSeeder, dll.)
        // sudah Anda salin ke folder LabSys_Integrated/database/seeders/
        // dan namespace-nya sudah benar (namespace Database\Seeders;).
        $this->call([
            UserRoleSeeder::class,     // Untuk membuat User Admin, Aslab, Mahasiswa contoh, dll.
            KategoriSeeder::class,     // Data Kategori
            InventarisSeeder::class,   // Data Inventaris (pastikan KategoriSeeder dijalankan dulu jika ada relasi)
            LaboratoriumSeeder::class, // Data Laboratorium
            //SettingSeeder::class,      // Data Settings Aplikasi
            LabBookingSeeder::class,   // Data Peminjaman/Booking Contoh
            // Tambahkan seeder lain di sini jika ada
        ]);

        // Jika UserRoleSeeder Anda tidak membuat user default yang cukup,
        // Anda bisa menggunakan factory di sini untuk membuat user tambahan.
        // Contoh: Membuat 5 user Mahasiswa tambahan jika UserRoleSeeder hanya buat Admin/Aslab.
        // User::factory(5)->create(['role' => 'Mahasiswa']);
    }
}