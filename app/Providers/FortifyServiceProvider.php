<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

// Tambahkan ini
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException; // Untuk melempar error validasi custom

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // --- AKTIFKAN DAN SESUAIKAN BLOK INI ---
        Fortify::authenticateUsing(function (Request $request) {
            // Fortify::username() akan mengembalikan 'npm' berdasarkan config/fortify.php
            $npm = $request->input(Fortify::username());
            $password = $request->input('password');
            $selectedRoleFromUI = $request->input('role_ui_selector_login'); // Ambil role dari UI

            $user = User::where('npm', $npm)->first();

            if ($user && Hash::check($password, $user->password)) {
                // Pengguna ditemukan dan password cocok, sekarang cek role jika dipilih di UI
                if ($selectedRoleFromUI) {
                    $dbRole = $user->role; // Role user dari database (misal: 'Mahasiswa', 'Aslab', 'Admin')

                    if ($selectedRoleFromUI === 'mahasiswa' && $dbRole !== 'Mahasiswa') {
                        // Memilih 'mahasiswa' di UI, tapi di DB bukan 'Mahasiswa'
                        throw ValidationException::withMessages([
                            Fortify::username() => [__('Login gagal: Role yang dipilih tidak sesuai dengan akun Anda.')],
                        ]);
                    } elseif ($selectedRoleFromUI === 'aslab') {
                        // Memilih 'aslab' di UI, kita izinkan jika role di DB adalah 'Aslab' ATAU 'Admin'
                        if (!in_array($dbRole, ['Aslab', 'Admin'])) {
                            throw ValidationException::withMessages([
                                Fortify::username() => [__('Login gagal: Role yang dipilih tidak sesuai dengan akun Anda.')],
                            ]);
                        }
                        // Jika lolos, berarti user adalah Aslab atau Admin, dan itu valid untuk pilihan 'aslab' di UI
                    }
                    // Jika tidak ada kondisi di atas yang cocok, berarti role sesuai atau tidak ada validasi role dari UI
                }
                // Jika tidak ada $selectedRoleFromUI atau role sudah valid, kembalikan user
                return $user;
            }

            // Jika user tidak ditemukan atau password tidak cocok
            throw ValidationException::withMessages([
                Fortify::username() => [__('auth.failed')], // Pesan error login standar
            ]);
        });
        // --- AKHIR BLOK ---

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());
            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}