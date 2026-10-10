<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictSellerApplication
{
    /**
     * Restrict seller application access to regular users.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('frontend.auth.login');
        }

        $user = auth()->user();

        $restrictedRoles = [
            'super-admin',
            'admin',
            'manager',
            'editor',
            'moderator',
            'support',
            'accountant',
            'seller',
        ];

        $roleNames = [];

        if (isset($user->role) && is_string($user->role)) {
            $roleNames[] = $user->role;
        }

        if (method_exists($user, 'roles')) {
            $roleNames = array_merge(
                $roleNames,
                $user->roles()->pluck('name')->all()
            );
        }

        $roleNames = array_map(
            static fn ($role) => strtolower(trim((string) $role)),
            $roleNames
        );

        if (array_intersect($restrictedRoles, $roleNames)) {
            abort(403, 'You are not allowed to access seller applications.');
        }

        return $next($request);
    }
}
