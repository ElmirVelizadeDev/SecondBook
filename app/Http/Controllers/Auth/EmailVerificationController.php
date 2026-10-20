<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AccountSecurityEvent;
use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;

class EmailVerificationController extends Controller
{
    public function emailVerify()
    {
        $email = session('email_verification_email');

        if (!$email) {
            return redirect()->route('frontend.auth.login');
        }

        return view('Auth.email-verify', compact('email'));
    }

    public function verifyEmailOtp(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Get Pending Registration
        |--------------------------------------------------------------------------
        */

        $pendingRegistration = session('pending_registration');
        $emailChange = Auth::user()
            && session('sensitive_action') === 'email_change'
            && strtolower((string) session('sensitive_action_email')) === strtolower((string) Auth::user()->email)
            && session('pending_email');
        $email = $emailChange
            ? session('pending_email')
            : session('email_verification_email');

        /*
        |--------------------------------------------------------------------------
        | Verification Session Check
        |--------------------------------------------------------------------------
        */

        if ((!$pendingRegistration && !$emailChange) || !$email) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Your registration session has expired. Please register again.',
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
        | Find OTP
        |--------------------------------------------------------------------------
        */

        $otp = Otp::where(
            'email',
            strtolower($email)
        )
            ->where(
                'purpose',
                $emailChange ? 'email_change' : 'email_verification'
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

        if ($emailChange) {
            $user = Auth::user();
            $newEmail = strtolower(trim((string) session('pending_email')));

            if (User::query()->whereRaw('LOWER(email) = ?', [$newEmail])->where('id', '!=', $user->id)->exists()) {
                $otp->delete();
                return response()->json([
                    'success' => false,
                    'message' => 'This email address is already registered.',
                    'errors' => ['email' => ['This email address is already registered.']],
                ], 422);
            }

            $user->forceFill([
                'email' => $newEmail,
                'email_verified_at' => now(),
            ])->save();
            AccountSecurityEvent::create([
                'user_id' => $user->id,
                'event' => 'email_updated',
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 1000),
            ]);
            RateLimiter::clear('account-email-change:' . $user->id);
            $otp->delete();
            session()->forget([
                'sensitive_action', 'sensitive_action_email',
                'pending_email', 'email_verification_email',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Your new email address has been verified.',
                'redirect' => route('frontend.account.settings'),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Final Username Check
        |--------------------------------------------------------------------------
        |
        | Someone could register the same username while the first
        | registration is waiting for OTP.
        |--------------------------------------------------------------------------
        */

        if (
            User::where(
                'username',
                $pendingRegistration['username']
            )->exists()
        ) {
            $otp->delete();

            session()->forget([
                'pending_registration',
                'email_verification_email',
            ]);

            return response()->json([
                'success' => false,

                'message' =>
                    'This username is no longer available. Please register again.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Final Email Check
        |--------------------------------------------------------------------------
        */

        if (
            User::whereRaw(
                'LOWER(email) = ?',
                [
                    strtolower(
                        $pendingRegistration['email']
                    )
                ]
            )->exists()
        ) {
            $otp->delete();

            session()->forget([
                'pending_registration',
                'email_verification_email',
            ]);

            return response()->json([
                'success' => false,

                'message' =>
                    'This email is already registered. Please log in instead.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Create User ONLY AFTER OTP Verification
        |--------------------------------------------------------------------------
        */

        $user = User::create([
            'name' =>
                $pendingRegistration['first_name']
                . ' '
                . $pendingRegistration['last_name'],

            'first_name' =>
                $pendingRegistration['first_name'],

            'last_name' =>
                $pendingRegistration['last_name'],

            'username' =>
                $pendingRegistration['username'],

            'email' =>
                $pendingRegistration['email'],

            'password' =>
                $pendingRegistration['password'],

            'role' =>
                'user',

            'status' =>
                'active',

            'email_verified_at' =>
                now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Delete Used OTP
        |--------------------------------------------------------------------------
        */

        $otp->delete();

        /*
        |--------------------------------------------------------------------------
        | Clear Registration Session
        |--------------------------------------------------------------------------
        */

        session()->forget([
            'pending_registration',
            'email_verification_email',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Login User Automatically
        |--------------------------------------------------------------------------
        */

        Auth::login($user);

        /*
        |--------------------------------------------------------------------------
        | Regenerate Session
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | Update Last Login
        |--------------------------------------------------------------------------
        */

        $user->update([
            'last_login_at' => now(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,

            'message' =>
                'Your email has been verified successfully. Welcome to SecondBook!',

            'redirect' =>
                route('frontend.home'),
        ]);
    }
}
