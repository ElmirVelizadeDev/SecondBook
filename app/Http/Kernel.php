<?php

namespace App\Http;

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
        // \App\Http\Middleware\TrustHosts::class,
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

        /*
        |--------------------------------------------------------------------------
        | Web Middleware
        |--------------------------------------------------------------------------
        */

        'web' => [

            \App\Http\Middleware\EncryptCookies::class,

            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,

            /*
            |--------------------------------------------------------------------------
            | Session
            |--------------------------------------------------------------------------
            */

            \Illuminate\Session\Middleware\StartSession::class,

            /*
            |--------------------------------------------------------------------------
            | Dynamic Session Lifetime
            |--------------------------------------------------------------------------
            */

            \App\Http\Middleware\DynamicSessionLifetime::class,

            \Illuminate\View\Middleware\ShareErrorsFromSession::class,

            \App\Http\Middleware\VerifyCsrfToken::class,

            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        /*
        |--------------------------------------------------------------------------
        | API Middleware
        |--------------------------------------------------------------------------
        */

        'api' => [

            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,

            \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',

            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used instead of class names to conveniently
     * assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [

        /*
        |--------------------------------------------------------------------------
        | Authentication
        |--------------------------------------------------------------------------
        */

        'auth' => \App\Http\Middleware\Authenticate::class,

        'seller' => \App\Http\Middleware\SellerMiddleware::class,

        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,

        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,

        /*
        |--------------------------------------------------------------------------
        | Cache
        |--------------------------------------------------------------------------
        */

        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,

        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        */

        'admin' => \App\Http\Middleware\AdminMiddleware::class,

        'permission' => \App\Http\Middleware\PermissionMiddleware::class,

        /*
        |--------------------------------------------------------------------------
        | Authorization
        |--------------------------------------------------------------------------
        */

        'can' => \Illuminate\Auth\Middleware\Authorize::class,

        /*
        |--------------------------------------------------------------------------
        | Guest
        |--------------------------------------------------------------------------
        */

        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,

        /*
        |--------------------------------------------------------------------------
        | Password Confirmation
        |--------------------------------------------------------------------------
        */

        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,

        /*
        |--------------------------------------------------------------------------
        | Precognitive Requests
        |--------------------------------------------------------------------------
        */

        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,

        /*
        |--------------------------------------------------------------------------
        | Signed URLs
        |--------------------------------------------------------------------------
        */

        'signed' => \App\Http\Middleware\ValidateSignature::class,

        /*
        |--------------------------------------------------------------------------
        | Throttle
        |--------------------------------------------------------------------------
        */

        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,

        /*
        |--------------------------------------------------------------------------
        | Email Verification
        |--------------------------------------------------------------------------
        */

        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];
}