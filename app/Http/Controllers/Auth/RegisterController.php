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

class RegisterController extends Controller
{
    public function register()
    {
        return view('Auth.register');
    }

    public function storeRegister(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Check Registration Status
        |--------------------------------------------------------------------------
        */

        if (!Setting::get('user_registration_enabled', true)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User registration is currently disabled.',
                ], 403);
            }

            return back()->with(
                'error',
                'User registration is currently disabled.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Normalize Input
        |--------------------------------------------------------------------------
        */

        $request->merge([
            'first_name' => trim((string) $request->first_name),
            'last_name'  => trim((string) $request->last_name),
            'username'   => trim((string) $request->username),
            'email'      => strtolower(trim((string) $request->email)),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        |
        | The regex requires a real domain ending with a TLD.
        |
        | Valid:
        | elmir@gmail.com
        | elmir@yahoo.com
        | elmir@outlook.com
        |
        | Invalid:
        | elmir@gmail
        | elmir@
        | @gmail.com
        | elmir@gmail.
        |
        |--------------------------------------------------------------------------
        */

        $request->validate(
            [
                'first_name' => [
                    'required',
                    'string',
                    'min:2',
                    'max:50',
                    'regex:/^[\pL\pM]+(?:[\'\-\s][\pL\pM]+)*$/u',
                ],

                'last_name' => [
                    'required',
                    'string',
                    'min:2',
                    'max:50',
                    'regex:/^[\pL\pM]+(?:[\'\-\s][\pL\pM]+)*$/u',
                ],

                'username' => [
                    'required',
                    'string',
                    'min:3',
                    'max:100',
                    'regex:/^[A-Za-z0-9\_.-]+$/',
                    'unique:users,username',
                ],

                'email' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[A-Za-z0-9.!#$%&\'*+\/=?^_`{|}~-]+@(gmail\.com|yahoo\.com|outlook\.com|hotmail\.com|icloud\.com|protonmail\.com|gmx\.com|mail\.com|zoho\.com|yandex\.com)$/i',
                    'unique:users,email',
                ],

                'password' => [
                    'required',
                    'string',
                    'min:' . Setting::get(
                        'minimum_password_length',
                        8
                    ),
                    'max:128',
                    'confirmed',
                ],

                'terms' => [
                    'accepted',
                ],
            ],

            [
                'first_name.required' =>
                    'First name is required.',

                'first_name.min' =>
                    'First name must be at least 2 characters.',

                'first_name.max' =>
                    'First name may not exceed 50 characters.',

                'first_name.regex' =>
                    'First name may contain letters, spaces, hyphens, or apostrophes only.',

                'last_name.required' =>
                    'Last name is required.',

                'last_name.min' =>
                    'Last name must be at least 2 characters.',

                'last_name.max' =>
                    'Last name may not exceed 50 characters.',

                'last_name.regex' =>
                    'Last name may contain letters, spaces, hyphens, or apostrophes only.',

                'username.required' =>
                    'Username is required.',

                'username.min' =>
                    'Username must be at least 3 characters.',

                'username.max' =>
                    'Username may not exceed 100 characters.',

                'username.regex' =>
                    'Username may contain letters, numbers, dots, underscores, and hyphens only.',

                'username.unique' =>
                    'This username is already taken.',

                'email.email' =>
                    'Please enter a valid email address.',

                'email.regex' =>
                    'Please enter a valid email address. Allowed providers: Gmail, Yahoo, Outlook, Hotmail, iCloud, ProtonMail, GMX, Mail.com, Zoho, or Yandex.',

                'email.max' =>
                    'Email address may not exceed 255 characters.',

                'email.unique' =>
                    'This email is already registered.',

                'password.required' =>
                    'Password is required.',

                'password.min' =>
                    'Password does not meet the minimum length requirement.',

                'password.max' =>
                    'Password may not exceed 128 characters.',

                'password.confirmed' =>
                    'Password confirmation does not match.',

                'terms.accepted' =>
                    'You must accept the Terms and Conditions.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Generate OTP
        |--------------------------------------------------------------------------
        */

        $email = strtolower(
            trim($request->email)
        );

        $otp = (string) random_int(
            100000,
            999999
        );

        /*
        |--------------------------------------------------------------------------
        | Store Pending Registration
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | No User::create() here.
        |
        | The account will only be created after OTP verification.
        |--------------------------------------------------------------------------
        */

        session([
            'pending_registration' => [
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'username'   => $request->username,
                'email'      => $email,
                'password'   => Hash::make($request->password),
            ],

            'email_verification_email' => $email,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Store Registration OTP
        |--------------------------------------------------------------------------
        */

        Otp::updateOrCreate(
            [
                'email' =>
                    $email,

                'purpose' =>
                    'email_verification',
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
        | Send Verification Email
        |--------------------------------------------------------------------------
        */

        try {
            Mail::raw(
                "Your SecondBook email verification code is: {$otp}\n\n"
                . "This code will expire in 10 minutes.",

                function ($message) use ($email) {
                    $message
                        ->to($email)
                        ->subject(
                            'SecondBook Email Verification'
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
                    'email_verification'
                )
                ->delete();

            session()->forget([
                'pending_registration',
                'email_verification_email',
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'Unable to send the verification code. Please try again.',
                ], 500);
            }

            return back()
                ->withErrors([
                    'email' =>
                        'Unable to send the verification code. Please try again.',
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Success
        |--------------------------------------------------------------------------
        */

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,

                'message' =>
                    'A verification code has been sent to your email.',

                'redirect' =>
                    route(
                        'frontend.auth.email.verify'
                    ),
            ]);
        }

        return redirect()
            ->route(
                'frontend.auth.email.verify'
            )
            ->with(
                'status',
                'Please enter the verification code sent to your email.'
            );
    }
}
