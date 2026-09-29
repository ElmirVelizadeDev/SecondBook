<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
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
        | Authentication Check
        |--------------------------------------------------------------------------
        */

        if (!Auth::check()) {
            return redirect()
                ->route('frontend.auth.login');
        }

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Allowed Admin Roles
        |--------------------------------------------------------------------------
        */

        $allowedRoles = [
            'admin',
            'manager',
            'editor',
            'moderator',
            'support',
            'accountant',
        ];

        /*
        |--------------------------------------------------------------------------
        | RBAC Role Check
        |--------------------------------------------------------------------------
        */

        $isSuperAdmin = $user->hasRole('super-admin');

        $hasAllowedRole = $user->roles()
            ->whereIn('name', $allowedRoles)
            ->exists();

        /*
        |--------------------------------------------------------------------------
        | Legacy Admin Role Support
        |--------------------------------------------------------------------------
        */

        $legacyRole = strtolower(
            (string) ($user->role ?? '')
        );

        $hasLegacyAdminRole = in_array(
            $legacyRole,
            [
                'admin',
                'superadmin',
                'administrator',
            ],
            true
        );

        /*
        |--------------------------------------------------------------------------
        | Final Admin Access Check
        |--------------------------------------------------------------------------
        */

        if (
            !$isSuperAdmin &&
            !$hasAllowedRole &&
            !$hasLegacyAdminRole
        ) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | Account Status Check
        |--------------------------------------------------------------------------
        |
        | Admin accounts must remain active.
        |
        */

        $status = strtolower(
            (string) ($user->status ?? '')
        );

        if ($status !== 'active') {

            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect()
                ->route('frontend.auth.login')
                ->with(
                    'error',
                    'Your account no longer has access to the admin panel.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Two-Factor Authentication
        |--------------------------------------------------------------------------
        */

        $twoFactorEnabled = (bool) Setting::get(
            'two_factor_authentication_enabled',
            false
        );

        if ($twoFactorEnabled) {

            /*
            |--------------------------------------------------------------------------
            | Require Successful 2FA Verification
            |--------------------------------------------------------------------------
            |
            | Admin users cannot access the panel until the OTP
            | verification has been completed.
            |
            */

            if (!$request->session()->get('admin_2fa_verified', false)) {

                Auth::logout();

                $request->session()->invalidate();

                $request->session()->regenerateToken();

                return redirect()
                    ->route('frontend.auth.login')
                    ->with(
                        'error',
                        'Two-factor authentication is required to access the admin panel.'
                    );
            }
        } else {

            /*
            |--------------------------------------------------------------------------
            | Clear Old 2FA Verification State
            |--------------------------------------------------------------------------
            */

            $request->session()->forget('admin_2fa_verified');
        }

        /*
        |--------------------------------------------------------------------------
        | Admin Session Security
        |--------------------------------------------------------------------------
        */

        $adminSessionSecurity = (bool) Setting::get(
            'admin_session_security',
            true
        );

        if ($adminSessionSecurity) {

            /*
            |--------------------------------------------------------------------------
            | Generate Session Fingerprint
            |--------------------------------------------------------------------------
            |
            | We intentionally do NOT use the user's IP address.
            |
            | A user's IP can change during a normal session, especially
            | on mobile networks, VPNs or changing networks.
            |
            */

            $currentFingerprint = hash(
                'sha256',
                $user->getAuthIdentifier()
                . '|'
                . (string) $request->userAgent()
            );

            $storedFingerprint = $request->session()->get(
                'admin_security_fingerprint'
            );

            /*
            |--------------------------------------------------------------------------
            | First Request
            |--------------------------------------------------------------------------
            |
            | If this is a newly authenticated admin session, save
            | the fingerprint.
            |
            */

            if ($storedFingerprint === null) {

                $request->session()->put(
                    'admin_security_fingerprint',
                    $currentFingerprint
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Fingerprint Mismatch
            |--------------------------------------------------------------------------
            */

            elseif (
                !hash_equals(
                    (string) $storedFingerprint,
                    $currentFingerprint
                )
            ) {

                Auth::logout();

                $request->session()->invalidate();

                $request->session()->regenerateToken();

                return redirect()
                    ->route('frontend.auth.login')
                    ->with(
                        'error',
                        'Your admin session has been invalidated for security reasons. Please log in again.'
                    );
            }
        } else {

            /*
            |--------------------------------------------------------------------------
            | Clear Fingerprint When Disabled
            |--------------------------------------------------------------------------
            */

            $request->session()->forget(
                'admin_security_fingerprint'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Continue Request
        |--------------------------------------------------------------------------
        */

        return $next($request);
    }
}