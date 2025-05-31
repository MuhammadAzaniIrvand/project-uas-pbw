<?php

use Laravel\Fortify\Features;

return [

    // ... (bagian atas konfigurasi tetap sama) ...

    /*
    |--------------------------------------------------------------------------
    | Username / Email
    |--------------------------------------------------------------------------
    |
    | This value defines which model attribute should be considered as your
    | application's "username" field. Typically, this might be the email
    | address of the users but you are free to change this value here.
    |
    | Out of the box, Fortify expects forgot password and reset password
    | requests to have a field named 'email'. If the application uses
    | another name for the field you may define it below as needed.
    |
    */

    'username' => 'npm', // <-- PERUBAHAN UTAMA DI SINI: dari 'email' menjadi 'npm'

    'email' => 'email', // Biarkan 'email' untuk fitur reset password,
                        // kecuali Anda ingin mengkustomisasi reset password juga agar menggunakan NPM.
                        // Jika Anda juga mengubah ini menjadi 'npm', pastikan action ResetUserPassword
                        // dan view terkait juga disesuaikan untuk menerima NPM, bukan email.
                        // Untuk sekarang, fokus pada login, jadi biarkan 'email'.

    // ... (sisa konfigurasi tetap sama) ...

    /*
    |--------------------------------------------------------------------------
    | Home Path
    |--------------------------------------------------------------------------
    */
    'home' => '/dashboard',

    /*
    |--------------------------------------------------------------------------
    | Fortify Routes Prefix / Subdomain
    |--------------------------------------------------------------------------
    */
    'prefix' => '',
    'domain' => null,

    /*
    |--------------------------------------------------------------------------
    | Fortify Routes Middleware
    |--------------------------------------------------------------------------
    */
    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    */
    'limiters' => [
        'login' => 'login', // Rate limiter untuk login akan menggunakan $request->input(Fortify::username())
                            // yang sekarang akan menjadi $request->input('npm')
        'two-factor' => 'two-factor',
    ],

    /*
    |--------------------------------------------------------------------------
    | Register View Routes
    |--------------------------------------------------------------------------
    */
    'views' => true,

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    */
    'features' => [
        Features::registration(),
        Features::resetPasswords(),
        // Features::emailVerification(),
        Features::updateProfileInformation(),
        Features::updatePasswords(),
        Features::twoFactorAuthentication([
            'confirm' => true,
            'confirmPassword' => true,
            // 'window' => 0,
        ]),
    ],

];