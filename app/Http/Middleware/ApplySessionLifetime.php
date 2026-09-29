<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplySessionLifetime
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $sessionLifetime = (int) Setting::get(
            'session_lifetime',
            120
        );

        $sessionLifetime = max(
            1,
            min($sessionLifetime, 10080)
        );

        config([
            'session.lifetime' => $sessionLifetime,
        ]);

        return $next($request);
    }
}