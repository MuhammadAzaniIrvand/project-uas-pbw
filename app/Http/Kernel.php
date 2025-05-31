<?php

namespace App\Http; // Pastikan namespace ini benar

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // Middleware global standar Laravel
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class, // Jetstream akan menambahkan ini jika perlu
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // Untuk Laravel 11+, Sanctum biasanya dikonfigurasi di bootstrap/app.php
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used to conveniently assign middleware to routes and groups.
     * PENTING: Di Laravel 11+, ini adalah `$middlewareAliases`.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [ // <--- PERHATIKAN PERUBAHAN NAMA PROPERTI INI
        'auth' => \App\Http\Middleware\Authenticate::class, // Atau \Illuminate\Auth\Middleware\Authenticate::class jika Anda tidak membuat kustom
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // Alias untuk middleware role Anda
        'role' => \App\Http\Middleware\EnsureUserHasRole::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     * Di Laravel 11, ini juga bisa dikonfigurasi di bootstrap/app.php jika Anda menggunakan cara baru.
     *
     * @var string[]
     */
    // protected $middlewarePriority = [
    //     \Illuminate\Cookie\Middleware\EncryptCookies::class,
    //     \Illuminate\Session\Middleware\StartSession::class,
    //     \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    //     \Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests::class, // Atau Authenticate::class
    //     \Illuminate\Session\Middleware\AuthenticateSession::class,
    //     \Illuminate\Routing\Middleware\SubstituteBindings::class,
    //     \Illuminate\Auth\Middleware\Authorize::class,
    // ];
}