<?php

namespace App\Http\Controllers\Auth;

use Throwable;

use App\Http\Controllers\Controller;
use App\Models\AccountSecurityEvent;
use App\Models\Otp;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PasswordResetController extends Controller
{
    public function passwordRequest()
    {
        return view(
            'Auth.password-request'
        );
    }

    public function sendOtp(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Normalize Email
        |--------------------------------------------------------------------------
        */

        $request->merge([
            'email' => strtolower(
                trim(
                    (string) $request->email
                )
            ),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make(
            $request->all(),
            [
                'email' => [
                    'required',
                    'string',
                    'email:rfc',
                    'max:255',
                ],
            ],

            [
                'email.required' =>
                    'Email address is required.',

                'email.email' =>
                    'Please enter a valid email address.',

                'email.max' =>
                    'Email address may not exceed 255 characters.',
            ]
        );

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        $validator->errors()->first(),
                    'errors' =>
                        $validator->errors(),
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Find User
        |--------------------------------------------------------------------------
        */

        $user = User::whereRaw(
            'LOWER(email) = ?',
            [$request->email]
        )->first();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'No account was found with this email address.',
                    'errors' => [
                        'email' => [
                            'No account was found with this email address.',
                        ],
                    ],
                ], 422);
            }

            return back()
                ->withErrors([
                    'email' =>
                        'No account was found with this email address.',
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Clear Any Previous Sensitive Action
        |--------------------------------------------------------------------------
        */

        session()->forget([
            'sensitive_action',
            'sensitive_action_email',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Generate OTP
        |--------------------------------------------------------------------------
        */

        $otp = random_int(
            100000,
            999999
        );

        /*
        |--------------------------------------------------------------------------
        | Store Password Reset OTP
        |--------------------------------------------------------------------------
        */

        Otp::updateOrCreate(
            [
                'email' =>
                    $request->email,

                'purpose' =>
                    'password_reset',
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
        | Send Email
        |--------------------------------------------------------------------------
        */

        try {
            Mail::raw(
                "Your SecondBook password reset code is: {$otp}",

                function ($message) use ($request) {
                    $message
                        ->to($request->email)
                        ->subject(
                            'SecondBook Password Reset OTP'
                        );
                }
            );

        } catch (Throwable $e) {
            report($e);

            Otp::where(
                'email',
                $request->email
            )
                ->where(
                    'purpose',
                    'password_reset'
                )
                ->delete();

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
        | Store Reset Email
        |--------------------------------------------------------------------------
        */

        session([
            'reset_email' =>
                $request->email,
        ]);

        /*
        |--------------------------------------------------------------------------
        | AJAX Success
        |--------------------------------------------------------------------------
        */

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,

                'message' =>
                    'Verification code sent successfully.',

                'redirect' =>
                    route(
                        'frontend.auth.password.verify'
                    ),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Normal Request Success
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'frontend.auth.password.verify'
            )
            ->with(
                'status',
                'Please enter the verification code sent to your email.'
            );
    }

    public function verifyOtp(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Determine Sensitive Action
        |--------------------------------------------------------------------------
        */

        $sensitiveAction =
            session('sensitive_action');

        $sensitiveEmail =
            session('sensitive_action_email');

        $authenticatedUser =
            Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Password Reset Flow
        |--------------------------------------------------------------------------
        */

        if (
            $sensitiveAction === 'password_change' &&
            $authenticatedUser &&
            $sensitiveEmail &&
            strtolower($sensitiveEmail) ===
                strtolower($authenticatedUser->email)
        ) {
            $purpose = 'password_change';
        } else {
            $purpose = 'password_reset';
        }

        /*
        |--------------------------------------------------------------------------
        | Normalize OTP
        |--------------------------------------------------------------------------
        */

        $request->merge([
            'otp_code' =>
                trim(
                    (string) $request->otp_code
                ),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Base Validation
        |--------------------------------------------------------------------------
        */

        $rules = [
            'otp_code' => [
                'required',
                'digits:6',
            ],
        ];

        $messages = [
            'otp_code.required' =>
                'Verification code is required.',

            'otp_code.digits' =>
                'Verification code must be exactly 6 digits.',
        ];

        /*
        |--------------------------------------------------------------------------
        | Password Validation
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $purpose,
                [
                    'password_reset',
                    'password_change',
                ],
                true
            )
        ) {
            $minimumPasswordLength =
                Setting::get(
                    'minimum_password_length',
                    8
                );

            $rules['password'] = [
                'required',
                'string',
                'min:' . $minimumPasswordLength,
                'max:128',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'confirmed',
            ];

            $messages['password.required'] =
                'New password is required.';

            $messages['password.min'] =
                'Password must be at least ' .
                $minimumPasswordLength .
                ' characters.';

            $messages['password.max'] =
                'Password may not exceed 128 characters.';

            $messages['password.regex'] =
                'Password must contain at least one lowercase letter and one number.';

            $messages['password.confirmed'] =
                'Password confirmation does not match.';
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        */

        $validator = Validator::make(
            $request->all(),
            $rules,
            $messages
        );

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,

                    'message' =>
                        $validator->errors()->first(),

                    'errors' =>
                        $validator->errors(),
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Get Email According To Purpose
        |--------------------------------------------------------------------------
        */

        if ($purpose === 'password_reset') {
            $email = session('reset_email');

            if (!$email) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' =>
                            'Your password reset session has expired. Please request a new code.',
                    ], 422);
                }

                return redirect()
                    ->route(
                        'frontend.auth.password.request'
                    )
                    ->withErrors([
                        'email' =>
                            'Your password reset session has expired. Please request a new code.',
                    ]);
            }
        } else {
            $email = $sensitiveEmail;

            if (
                !$authenticatedUser ||
                !$email ||
                strtolower($email) !==
                    strtolower($authenticatedUser->email)
            ) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' =>
                            'Your verification session has expired. Please start the action again.',
                    ], 422);
                }

                return redirect()
                    ->route(
                        'frontend.account.settings'
                    )
                    ->with(
                        'error',
                        'Your verification session has expired. Please start the action again.'
                    );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Find OTP
        |--------------------------------------------------------------------------
        */

        $otp = Otp::where(
            'email',
            $email
        )
            ->where(
                'purpose',
                $purpose
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
            if ($request->expectsJson()) {
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

            return back()
                ->withErrors([
                    'otp_code' =>
                        'Invalid verification code.',
                ])
                ->withInput();
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

            if ($request->expectsJson()) {
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

            return back()
                ->withErrors([
                    'otp_code' =>
                        'This verification code has expired.',
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | Find User For Password Operations
        |--------------------------------------------------------------------------
        */

        if ($purpose === 'password_change') {
            $user = $authenticatedUser;
        } else {
            $user = User::whereRaw(
                'LOWER(email) = ?',
                [strtolower($otp->email)]
            )->first();
        }

        /*
        |--------------------------------------------------------------------------
        | User Not Found
        |--------------------------------------------------------------------------
        */

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,

                    'message' =>
                        'The account associated with this code could not be found.',
                ], 404);
            }

            return back()
                ->withErrors([
                    'email' =>
                        'User not found.',
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | New Password Must Be Different
        |--------------------------------------------------------------------------
        */

        if (
            Hash::check(
                $request->password,
                $user->password
            )
        ) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,

                    'message' =>
                        'Your new password must be different from your current password.',

                    'errors' => [
                        'password' => [
                            'Your new password must be different from your current password.',
                        ],
                    ],
                ], 422);
            }

            return back()
                ->withErrors([
                    'password' =>
                        'Your new password must be different from your current password.',
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Update Password
        |--------------------------------------------------------------------------
        */

        $user->update([
            'password' =>
                Hash::make(
                    $request->password
                ),
        ]);

        if ($purpose === 'password_change') {
            AccountSecurityEvent::create([
                'user_id' => $user->id,
                'event' => 'password_changed',
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 1000),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Used OTP
        |--------------------------------------------------------------------------
        */

        $otp->delete();

        /*
        |--------------------------------------------------------------------------
        | Password Change
        |--------------------------------------------------------------------------
        */

        if ($purpose === 'password_change') {
            session()->forget([
                'sensitive_action',
                'sensitive_action_email',
            ]);

            session()->flash(
                'success',
                'Your password has been updated successfully.'
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,

                    'message' =>
                        'Your password has been updated successfully.',

                    'redirect' =>
                        route(
                            'frontend.account.settings'
                        ) . '#security',
                ]);
            }

            return redirect()
                ->to(
                    route(
                        'frontend.account.settings'
                    ) . '#security'
                )
                ->with(
                    'success',
                    'Your password has been updated successfully.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Password Reset
        |--------------------------------------------------------------------------
        */

        session()->forget('reset_email');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,

                'message' =>
                    'Your password has been reset successfully.',

                'redirect' =>
                    route(
                        'frontend.auth.login'
                    ),
            ]);
        }

        return redirect()
            ->route(
                'frontend.auth.login'
            )
            ->with(
                'status',
                'Password reset successfully.'
            );
    }
}
