<?php

namespace App\Http\Controllers\Auth;

use Throwable;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    public function login()
    {
        return view('Auth.login');
    }

    public function storeLogin(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => [
                    'required',
                    'string',
                    'email:rfc',
                    'max:254',
                ],

                'password' => [
                    'required',
                    'string',
                    'max:128',
                ],
            ],
            [
                'email.required' =>
                    'Email address is required.',

                'email.email' =>
                    'Please enter a valid email address.',

                'email.max' =>
                    'Email address may not exceed 254 characters.',

                'password.required' =>
                    'Password is required.',

                'password.max' =>
                    'Password may not exceed 128 characters.',
            ]
        );

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors(),
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->withInput(
                    $request->only('email', 'remember')
                );
        }

        $credentials = $validator->validated();

        $remember = $request->boolean('remember');

        $email = trim(
            strtolower($credentials['email'])
        );

        $password = $credentials['password'];

        /*
        |--------------------------------------------------------------------------
        | Login Rate Limiting
        |--------------------------------------------------------------------------
        */

        $maxAttempts = (int) Setting::get(
            'login_attempt_limit',
            5
        );

        $maxAttempts = max(
            1,
            min($maxAttempts, 20)
        );

        $rateLimitKey = Str::transliterate(
            Str::lower($email) . '|' . $request->ip()
        );

        if (RateLimiter::tooManyAttempts(
            $rateLimitKey,
            $maxAttempts
        )) {
            $seconds = RateLimiter::availableIn(
                $rateLimitKey
            );

            $message =
                'Too many login attempts. Please try again in '
                . ceil($seconds / 60)
                . ' minute(s).';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 429);
            }

            return back()
                ->with('error', $message)
                ->withInput(
                    $request->only(
                        'email',
                        'remember'
                    )
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Find User
        |--------------------------------------------------------------------------
        */

        $user = User::whereRaw(
            'LOWER(email) = ?',
            [$email]
        )->first();

        if ($user) {

            $passwordValid = false;

            /*
            |--------------------------------------------------------------------------
            | Hashed Password
            |--------------------------------------------------------------------------
            */

            if (
                $user->password &&
                Hash::check(
                    $password,
                    $user->password
                )
            ) {
                $passwordValid = true;
            }

            /*
            |--------------------------------------------------------------------------
            | Legacy Plaintext Password Support
            |--------------------------------------------------------------------------
            */

            elseif (
                $user->password &&
                $password === $user->password
            ) {
                $passwordValid = true;

                $user->password = Hash::make(
                    $password
                );

                $user->save();
            }

            if ($passwordValid) {

                /*
                |--------------------------------------------------------------------------
                | Email Verification
                |--------------------------------------------------------------------------
                */

                if (is_null($user->email_verified_at)) {

                    session([
                        'email_verification_email' =>
                            strtolower($user->email),
                    ]);

                    return response()->json([
                        'success' => false,

                        'message' =>
                            'Please verify your email address before signing in.',

                        'redirect' =>
                            route(
                                'frontend.auth.email.verify'
                            ),
                    ], 403);
                }

                /*
                |--------------------------------------------------------------------------
                | Account Status
                |--------------------------------------------------------------------------
                */

                $status = strtolower(
                    (string) $user->status
                );

                if (
                    in_array(
                        $status,
                        [
                            'inactive',
                            'banned',
                        ],
                        true
                    )
                ) {

                    RateLimiter::hit(
                        $rateLimitKey,
                        60
                    );

                    $message =
                        $status === 'banned'
                            ? 'Your account has been restricted.'
                            : 'Your account is currently inactive.';

                    if ($request->expectsJson()) {
                        return response()->json([
                            'success' => false,
                            'message' => $message,
                        ], 403);
                    }

                    return back()
                        ->with(
                            'error',
                            $message
                        )
                        ->withInput(
                            $request->only(
                                'email',
                                'remember'
                            )
                        );
                }

                /*
                |--------------------------------------------------------------------------
                | Successful Password Validation
                |--------------------------------------------------------------------------
                */

                RateLimiter::clear(
                    $rateLimitKey
                );

                /*
                |--------------------------------------------------------------------------
                | Admin Two-Factor Authentication
                |--------------------------------------------------------------------------
                */

                if (
                    $user->isAdmin() &&
                    (bool) Setting::get(
                        'two_factor_authentication_enabled',
                        false
                    )
                ) {
                    return $this->startAdminTwoFactorAuthentication(
                        $request,
                        $user,
                        $remember
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Normal Login
                |--------------------------------------------------------------------------
                */

                Auth::login(
                    $user,
                    $remember
                );

                /*
                |--------------------------------------------------------------------------
                | Regenerate Session
                |--------------------------------------------------------------------------
                */

                $request->session()->regenerate();

                /*
                |--------------------------------------------------------------------------
                | Clear Security State
                |--------------------------------------------------------------------------
                */

                $request->session()->forget([
                    'admin_2fa_verified',
                    'admin_security_fingerprint',
                    'admin_2fa_pending_user_id',
                    'admin_2fa_pending_remember',
                    'admin_2fa_pending_email',
                ]);

                /*
                |--------------------------------------------------------------------------
                | Last Login
                |--------------------------------------------------------------------------
                */

                $user->update([
                    'last_login_at' => now(),
                ]);

                /*
                |--------------------------------------------------------------------------
                | Redirect By Role
                |--------------------------------------------------------------------------
                */

                $redirectUrl = $user->isAdmin()
                    ? route('admin.dashboard')
                    : route('frontend.home');

                /*
                |--------------------------------------------------------------------------
                | AJAX Success
                |--------------------------------------------------------------------------
                */

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'redirect' => $redirectUrl,
                    ]);
                }

                return redirect()
                    ->to($redirectUrl);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Invalid Login
        |--------------------------------------------------------------------------
        */

        RateLimiter::hit(
            $rateLimitKey,
            60
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Invalid email or password.',
            ], 422);
        }

        return back()
            ->with(
                'error',
                'Invalid email or password.'
            )
            ->withInput(
                $request->only(
                    'email',
                    'remember'
                )
            );
    }

    private function startAdminTwoFactorAuthentication(
        Request $request,
        User $user,
        bool $remember
    ) {
        /*
        |--------------------------------------------------------------------------
        | Clear Old 2FA State
        |--------------------------------------------------------------------------
        */

        $request->session()->forget([
            'admin_2fa_verified',
            'admin_security_fingerprint',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Generate OTP
        |--------------------------------------------------------------------------
        */

        $otp = (string) random_int(
            100000,
            999999
        );

        $email = strtolower(
            trim($user->email)
        );

        /*
        |--------------------------------------------------------------------------
        | Store Pending Admin Login
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | The user is NOT authenticated yet.
        |
        */

        $request->session()->put([
            'admin_2fa_pending_user_id' =>
                $user->id,

            'admin_2fa_pending_remember' =>
                $remember,

            'admin_2fa_pending_email' =>
                $email,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Store OTP
        |--------------------------------------------------------------------------
        */

        Otp::updateOrCreate(
            [
                'email' =>
                    $email,

                'purpose' =>
                    'admin_2fa',
            ],
            [
                'otp_code' =>
                    $otp,

                'expires_at' =>
                    Carbon::now()->addMinutes(10),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Send OTP Email
        |--------------------------------------------------------------------------
        */

        try {

            Mail::raw(
                "Your SecondBook admin security verification code is: {$otp}\n\n"
                . "This code will expire in 10 minutes.",

                function ($message) use ($email) {
                    $message
                        ->to($email)
                        ->subject(
                            'SecondBook Admin Security Verification'
                        );
                }
            );

        } catch (Throwable $e) {

            report($e);

            Otp::where(
                'email',
                $email
            )
                ->where(
                    'purpose',
                    'admin_2fa'
                )
                ->delete();

            $request->session()->forget([
                'admin_2fa_pending_user_id',
                'admin_2fa_pending_remember',
                'admin_2fa_pending_email',
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,

                    'message' =>
                        'Unable to send the security verification code. Please try again.',
                ], 500);
            }

            return redirect()
                ->route(
                    'frontend.auth.login'
                )
                ->with(
                    'error',
                    'Unable to send the security verification code. Please try again.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Session Fixation
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | Redirect To 2FA Page
        |--------------------------------------------------------------------------
        */

        $redirectUrl = route(
            'frontend.auth.admin.2fa'
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,

                'message' =>
                    'A security verification code has been sent to your email.',

                'redirect' =>
                    $redirectUrl,
            ]);
        }

        return redirect()
            ->to($redirectUrl)
            ->with(
                'status',
                'A security verification code has been sent to your email.'
            );
    }

    public function adminTwoFactor()
    {
        $userId = session(
            'admin_2fa_pending_user_id'
        );

        $email = session(
            'admin_2fa_pending_email'
        );

        if (!$userId || !$email) {
            return redirect()
                ->route(
                    'frontend.auth.login'
                )
                ->with(
                    'error',
                    'Your security verification session has expired. Please log in again.'
                );
        }

        $user = User::find($userId);

        if (!$user) {

            session()->forget([
                'admin_2fa_pending_user_id',
                'admin_2fa_pending_remember',
                'admin_2fa_pending_email',
            ]);

            return redirect()
                ->route(
                    'frontend.auth.login'
                )
                ->with(
                    'error',
                    'Your account could not be found. Please log in again.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Account Must Still Be Active
        |--------------------------------------------------------------------------
        */

        if (
            strtolower(
                (string) $user->status
            ) !== 'active'
        ) {

            session()->forget([
                'admin_2fa_pending_user_id',
                'admin_2fa_pending_remember',
                'admin_2fa_pending_email',
            ]);

            return redirect()
                ->route(
                    'frontend.auth.login'
                )
                ->with(
                    'error',
                    'Your account no longer has access to the admin panel.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Account Must Still Be Admin
        |--------------------------------------------------------------------------
        */

        if (!$user->isAdmin()) {

            session()->forget([
                'admin_2fa_pending_user_id',
                'admin_2fa_pending_remember',
                'admin_2fa_pending_email',
            ]);

            return redirect()
                ->route(
                    'frontend.auth.login'
                )
                ->with(
                    'error',
                    'Your account no longer has admin access.'
                );
        }

        return view(
            'Auth.admin-2fa-verify',
            compact('email')
        );
    }

    public function verifyAdminTwoFactor(
        Request $request
    ) {
        /*
        |--------------------------------------------------------------------------
        | Pending Session
        |--------------------------------------------------------------------------
        */

        $userId = session(
            'admin_2fa_pending_user_id'
        );

        $remember = session(
            'admin_2fa_pending_remember',
            false
        );

        $email = session(
            'admin_2fa_pending_email'
        );

        if (!$userId || !$email) {
            return response()->json([
                'success' => false,

                'message' =>
                    'Your security verification session has expired. Please log in again.',

                'redirect' =>
                    route(
                        'frontend.auth.login'
                    ),
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Validate OTP
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make(
            $request->all(),
            [
                'otp_code' => [
                    'required',
                    'digits:6',
                ],
            ],
            [
                'otp_code.required' =>
                    'Verification code is required.',

                'otp_code.digits' =>
                    'Verification code must be exactly 6 digits.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,

                'message' =>
                    $validator->errors()->first(),

                'errors' =>
                    $validator->errors(),
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Find Pending User
        |--------------------------------------------------------------------------
        */

        $user = User::find($userId);

        if (!$user) {

            session()->forget([
                'admin_2fa_pending_user_id',
                'admin_2fa_pending_remember',
                'admin_2fa_pending_email',
            ]);

            return response()->json([
                'success' => false,

                'message' =>
                    'Your account could not be found. Please log in again.',

                'redirect' =>
                    route(
                        'frontend.auth.login'
                    ),
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Recheck Account Status
        |--------------------------------------------------------------------------
        */

        if (
            strtolower(
                (string) $user->status
            ) !== 'active'
        ) {

            session()->forget([
                'admin_2fa_pending_user_id',
                'admin_2fa_pending_remember',
                'admin_2fa_pending_email',
            ]);

            return response()->json([
                'success' => false,

                'message' =>
                    'Your account no longer has access to the admin panel.',

                'redirect' =>
                    route(
                        'frontend.auth.login'
                    ),
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | Recheck Admin Access
        |--------------------------------------------------------------------------
        */

        if (!$user->isAdmin()) {

            session()->forget([
                'admin_2fa_pending_user_id',
                'admin_2fa_pending_remember',
                'admin_2fa_pending_email',
            ]);

            return response()->json([
                'success' => false,

                'message' =>
                    'Your account no longer has admin access.',

                'redirect' =>
                    route(
                        'frontend.auth.login'
                    ),
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | Find OTP
        |--------------------------------------------------------------------------
        */

        $otp = Otp::where(
            'email',
            strtolower($email)
        )
            ->where(
                'purpose',
                'admin_2fa'
            )
            ->where(
                'otp_code',
                $request->otp_code
            )
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Invalid OTP
        |--------------------------------------------------------------------------
        */

        if (!$otp) {
            return response()->json([
                'success' => false,

                'message' =>
                    'Invalid verification code. Please check the code and try again.',

                'errors' => [
                    'otp_code' => [
                        'Invalid verification code.',
                    ],
                ],
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Expired OTP
        |--------------------------------------------------------------------------
        */

        if (
            Carbon::now()->greaterThan(
                $otp->expires_at
            )
        ) {

            $otp->delete();

            return response()->json([
                'success' => false,

                'message' =>
                    'This verification code has expired. Please request a new code.',

                'errors' => [
                    'otp_code' => [
                        'This verification code has expired.',
                    ],
                ],
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Used OTP
        |--------------------------------------------------------------------------
        */

        $otp->delete();

        /*
        |--------------------------------------------------------------------------
        | Authenticate Admin
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | Authentication happens ONLY after successful OTP verification.
        |
        */

        Auth::login(
            $user,
            (bool) $remember
        );

        /*
        |--------------------------------------------------------------------------
        | Regenerate Authenticated Session
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | Mark 2FA As Verified
        |--------------------------------------------------------------------------
        */

        $request->session()->put(
            'admin_2fa_verified',
            true
        );

        /*
        |--------------------------------------------------------------------------
        | Admin Session Security Fingerprint
        |--------------------------------------------------------------------------
        */

        if (
            (bool) Setting::get(
                'admin_session_security',
                true
            )
        ) {

            $fingerprint = hash(
                'sha256',
                $user->getAuthIdentifier()
                . '|'
                . (string) $request->userAgent()
            );

            $request->session()->put(
                'admin_security_fingerprint',
                $fingerprint
            );
        } else {
            $request->session()->forget(
                'admin_security_fingerprint'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Clear Pending 2FA Session
        |--------------------------------------------------------------------------
        */

        $request->session()->forget([
            'admin_2fa_pending_user_id',
            'admin_2fa_pending_remember',
            'admin_2fa_pending_email',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Last Login
        |--------------------------------------------------------------------------
        */

        $user->update([
            'last_login_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Admin Dashboard
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,

            'message' =>
                'Two-factor authentication verified successfully.',

            'redirect' =>
                route(
                    'admin.dashboard'
                ),
        ]);
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser =
                Socialite::driver('google')->user();

            $email = strtolower(
                trim(
                    $googleUser->getEmail()
                )
            );

            $user = User::whereRaw(
                'LOWER(email) = ?',
                [$email]
            )->first();

            if (!$user) {
                $name = trim(
                    $googleUser->getName() ?? ''
                );

                $nameParts = preg_split(
                    '/\s+/',
                    $name,
                    2
                );

                $firstName =
                    $nameParts[0] ?? 'Google';

                $lastName =
                    $nameParts[1] ?? 'User';

                $baseUsername = strtolower(
                    preg_replace(
                        '/[^A-Za-z0-9\_.-]/',
                        '',
                        $googleUser->getNickname()
                            ?: $googleUser->getName()
                            ?: 'user'
                    )
                );

                if ($baseUsername === '') {
                    $baseUsername = 'user';
                }

                $username = $baseUsername;
                $counter = 1;

                while (
                    User::where(
                        'username',
                        $username
                    )->exists()
                ) {
                    $username =
                        $baseUsername . $counter;

                    $counter++;
                }

                $user = User::create([
                    'name' =>
                        $firstName . ' ' . $lastName,

                    'first_name' =>
                        $firstName,

                    'last_name' =>
                        $lastName,

                    'username' =>
                        $username,

                    'email' =>
                        $email,

                    'password' =>
                        Hash::make(
                            bin2hex(
                                random_bytes(32)
                            )
                        ),

                    'role' =>
                        'user',

                    'status' =>
                        'active',

                    'email_verified_at' =>
                        now(),
                ]);
            }

            if (is_null($user->email_verified_at)) {
                $user->update([
                    'email_verified_at' => now(),
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Google Admin Two-Factor Authentication
            |--------------------------------------------------------------------------
            */

            if (
                $user->isAdmin() &&
                (bool) Setting::get(
                    'two_factor_authentication_enabled',
                    false
                )
            ) {
                /*
                |--------------------------------------------------------------------------
                | Store Pending Google Admin Login
                |--------------------------------------------------------------------------
                */

                $request->session()->put([
                    'admin_2fa_pending_user_id' =>
                        $user->id,

                    'admin_2fa_pending_remember' =>
                        true,

                    'admin_2fa_pending_email' =>
                        strtolower($user->email),
                ]);

                /*
                |--------------------------------------------------------------------------
                | Generate OTP
                |--------------------------------------------------------------------------
                */

                $otpCode = (string) random_int(
                    100000,
                    999999
                );

                Otp::updateOrCreate(
                    [
                        'email' =>
                            strtolower($user->email),

                        'purpose' =>
                            'admin_2fa',
                    ],
                    [
                        'otp_code' =>
                            $otpCode,

                        'expires_at' =>
                            Carbon::now()->addMinutes(10),
                    ]
                );

                /*
                |--------------------------------------------------------------------------
                | Send OTP
                |--------------------------------------------------------------------------
                */

                try {

                    Mail::raw(
                        "Your SecondBook admin security verification code is: {$otpCode}\n\n"
                        . "This code will expire in 10 minutes.",

                        function ($message) use ($user) {
                            $message
                                ->to($user->email)
                                ->subject(
                                    'SecondBook Admin Security Verification'
                                );
                        }
                    );

                } catch (Throwable $e) {

                    report($e);

                    Otp::where(
                        'email',
                        strtolower($user->email)
                    )
                        ->where(
                            'purpose',
                            'admin_2fa'
                        )
                        ->delete();

                    $request->session()->forget([
                        'admin_2fa_pending_user_id',
                        'admin_2fa_pending_remember',
                        'admin_2fa_pending_email',
                    ]);

                    return redirect()
                        ->route(
                            'frontend.auth.login'
                        )
                        ->with(
                            'error',
                            'Unable to send the security verification code. Please try again.'
                        );
                }

                $request->session()->regenerate();

                return redirect()
                    ->route(
                        'frontend.auth.admin.2fa'
                    )
                    ->with(
                        'status',
                        'A security verification code has been sent to your email.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Normal Google Login
            |--------------------------------------------------------------------------
            */

            Auth::login(
                $user,
                true
            );

            $request->session()->regenerate();

            $request->session()->forget([
                'admin_2fa_verified',
                'admin_security_fingerprint',
            ]);

            $user->update([
                'last_login_at' => now(),
            ]);

            if ($user->isAdmin()) {
                return redirect()
                    ->route(
                        'admin.dashboard'
                    );
            }

            return redirect()
                ->route(
                    'frontend.home'
                );

        } catch (Throwable $e) {
            report($e);

            return redirect()
                ->route(
                    'frontend.auth.login'
                )
                ->with(
                    'error',
                    'Unable to sign in with Google. Please try again.'
                );
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Signed out successfully.',
            ]);
        }

        return redirect()
            ->route(
                'frontend.auth.login'
            );
    }

    public function showAdminTwoFactor()
    {
        if (!session()->has('admin_2fa_pending_user_id')) {
            return redirect()
                ->route('frontend.auth.login')
                ->with(
                    'error',
                    'Your two-factor authentication session has expired.'
                );
        }

        return view('Auth.admin-2fa');
    }
}
