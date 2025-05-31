<?php

namespace App\Actions\Fortify;

use App\Models\Team; // Pastikan path ini benar, biasanya App\Models\Team
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'npm' => ['required', 'string', 'max:255', 'unique:users,npm'], // Validasi NPM
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return DB::transaction(function () use ($input) {
            return tap(User::create([
                'name' => $input['name'],
                'npm' => $input['npm'], // Menyimpan NPM dari input
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'role' => 'Mahasiswa', // Menetapkan role default sebagai Mahasiswa
            ]), function (User $user) {
                $this->createTeam($user); // Panggil createTeam karena Anda menggunakan fitur teams
            });
        });
    }

    /**
     * Create a personal team for the user.
     */
    protected function createTeam(User $user): void
    {
        // Jika Anda menginstal dengan --teams, model Team seharusnya sudah ada di App\Models\Team
        // dan trait HasTeams sudah ada di User model, sehingga ownedTeams() akan tersedia.
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }
}