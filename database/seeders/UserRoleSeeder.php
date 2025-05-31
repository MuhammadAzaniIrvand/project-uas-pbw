<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Team; // Pastikan model Team di-import
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Laravel\Jetstream\Jetstream; // Untuk mendapatkan model Team Jetstream

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // --- Membuat User Admin ---
            $admin = User::updateOrCreate(
                ['email' => 'admin@labsys.test'],
                [
                    'name' => 'Muhammad Azani Irvand',
                    'npm' => 'ADMIN001',
                    'role' => 'Admin',
                    'password' => Hash::make('admin_password_123'),
                    'email_verified_at' => now(),
                ]
            );
            $this->createAndSetPersonalTeam($admin); // Panggil method yang diperbaiki

            // --- Membuat User Aslab ---
            $aslab = User::updateOrCreate(
                ['email' => 'aslab.informatika@labsys.test'],
                [
                    'name' => 'Aslab',
                    'npm' => 'ASLAB001',
                    'role' => 'Aslab',
                    'password' => Hash::make('aslab_password_123'),
                    'email_verified_at' => now(),
                ]
            );
            $this->createAndSetPersonalTeam($aslab); // Panggil method yang diperbaiki

            $this->command->info('User Admin dan Aslab berhasil dibuat/diupdate beserta tim personalnya.');
        });
    }

    /**
     * Create a personal team for the user and set it as current if not already set.
     */
    protected function createAndSetPersonalTeam(User $user): void
    {
        // Cek apakah user sudah memiliki personal team
        $personalTeam = $user->ownedTeams()->where('personal_team', true)->first();

        if (!$personalTeam) {
            // Jika belum ada, buat tim personal baru
            // Gunakan Jetstream::teamModel() untuk mendapatkan nama class model Team yang benar
            $teamModel = Jetstream::teamModel();
            $personalTeam = $user->ownedTeams()->create([ // Gunakan create() pada relasi HasMany
                'name' => explode(' ', $user->name, 2)[0]."'s Team",
                'personal_team' => true,
            ]);
        }

        // Jika user belum memiliki current_team_id atau current_team_id tidak valid,
        // set dengan ID personal team.
        // Method switchTeam() akan mengisi current_team_id dan memastikan user adalah member.
        if (is_null($user->current_team_id) || $user->current_team_id !== $personalTeam->id) {
            // switchTeam juga akan menambahkan user ke tim jika belum, dan set current_team_id
            // Tidak perlu attach manual karena ownedTeams adalah HasMany, bukan BelongsToMany
            $user->switchTeam($personalTeam);
        }
        // Jika user adalah pemilik tim (yang seharusnya terjadi dengan ownedTeams()->create()),
        // Jetstream biasanya sudah mengaturnya sebagai anggota.
        // Panggilan switchTeam() sudah cukup untuk mengatur current_team_id.
    }
}