<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Aktifkan jika Anda menggunakan verifikasi email Jetstream
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable; // Dari Jetstream
use Laravel\Jetstream\HasProfilePhoto;      // Dari Jetstream
use Laravel\Jetstream\HasTeams;             // Dari Jetstream (jika Anda menginstal dengan --teams)
use Laravel\Sanctum\HasApiTokens;           // Penting untuk API (jika masih dipakai) dan Jetstream
use App\Enums\UserRole;

// Tambahkan ini jika belum ada dan Anda menggunakan relasi Peminjaman
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable // (Opsional) implements MustVerifyEmail jika diaktifkan
{
    // --- TRAITS ---
    // Gabungkan semua trait yang dibutuhkan
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;            // Dari Jetstream
    use HasTeams;                   // Dari Jetstream (jika Anda menginstal dengan --teams)
    use Notifiable;
    use TwoFactorAuthenticatable;   // Dari Jetstream

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // Tambahkan field kustom Anda dari model lama:
        'npm',
        'role',
        // Jetstream mungkin menambahkan 'current_team_id', 'profile_photo_path' jika fitur Teams/Profile Photo aktif
        // Biarkan jika ada, atau tambahkan jika Anda akan menggunakannya.
        // 'current_team_id',
        // 'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        // Ambil dari Jetstream:
        'two_factor_recovery_codes',
        'two_factor_secret',
        // Jetstream mungkin menambahkan 'current_team_id' di sini
        // 'current_team_id',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        // Ambil dari Jetstream:
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array // Format baru untuk method casts di Laravel 10+
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Pastikan ini 'hashed'
            // Jetstream mungkin menambahkan cast untuk 'two_factor_confirmed_at'
            // 'two_factor_confirmed_at' => 'datetime',
        ];
    }

    // --- RELASI KUSTOM ANDA DARI MODEL LAMA ---
    public function peminjamanUser(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'user_id');
    }

    public function peminjamanDiproses(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'petugas_id');
    }

    // --- (Opsional) Method Bawaan Jetstream atau yang Anda tambahkan ---
    // Jetstream mungkin menambahkan method seperti isMemberOfTeam(), ownsTeam(), etc.
    // Biarkan method-method tersebut jika ada.

    // Jika Anda menggunakan role-based authorization, Anda mungkin punya method seperti ini:
    public function isAdmin(): bool
    {
        return $this->role === 'Admin'; // Sesuaikan dengan nama role Admin Anda
    }

    public function isAslab(): bool
    {
        return $this->role === 'Aslab'; // Sesuaikan dengan nama role Aslab Anda
    }

    public function isMahasiswa(): bool
    {
        return $this->role === 'Mahasiswa'; // Sesuaikan dengan nama role Mahasiswa Anda
    }
}