<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DynamicSessionLifetime
{
    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        /*
        |--------------------------------------------------------------------------
        | Only process authenticated users
        |--------------------------------------------------------------------------
        */

        if (Auth::check()) {

            /*
            |--------------------------------------------------------------------------
            | Get session lifetime from database settings
            |--------------------------------------------------------------------------
            */

            $lifetime = (int) Setting::get(
                'session_lifetime',
                config('session.lifetime', 120)
            );

            /*
            |--------------------------------------------------------------------------
            | Prevent invalid values
            |--------------------------------------------------------------------------
            |
            | Session lifetime must be at least 1 minute.
            |
            */

            $lifetime = max(1, $lifetime);

            /*
            |--------------------------------------------------------------------------
            | Check last activity
            |--------------------------------------------------------------------------
            */

            $lastActivity = $request->session()->get(
                'dynamic_session_last_activity'
            );

            if ($lastActivity !== null) {

                $inactiveSeconds = now()->timestamp - (int) $lastActivity;

                /*
                |--------------------------------------------------------------------------
                | Session expired because of inactivity
                |--------------------------------------------------------------------------
                */

                if ($inactiveSeconds >= ($lifetime * 60)) {

                    Auth::logout();

                    $request->session()->invalidate();

                    $request->session()->regenerateToken();

                    return redirect()
                        ->route('frontend.auth.login')
                        ->with(
                            'error',
                            'Your session has expired due to inactivity. Please log in again.'
                        );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Update last activity
            |--------------------------------------------------------------------------
            */

            $request->session()->put(
                'dynamic_session_last_activity',
                now()->timestamp
            );
        }

        return $next($request);
    }
}