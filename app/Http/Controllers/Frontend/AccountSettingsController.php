<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\Setting;
use App\Models\UserSetting;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Throwable;

class AccountSettingsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Account Settings
    |--------------------------------------------------------------------------
    */

    public function index(): View
    {
        $user = Auth::user();

        $settings = UserSetting::firstOrCreate(
            ['user_id' => $user->id],
            [
                'email_notifications' => true,
                'order_updates' => true,
                'promotional_emails' => false,
                'profile_visible' => true,
            ]
        );

        return view(
            'Frontend.account.settings',
            compact('user', 'settings')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Preferences
    |--------------------------------------------------------------------------
    |
    | Notifications və Privacy settings.
    |
    */

    public function updatePreferences(
        Request $request
    ): RedirectResponse|JsonResponse {
        $validator = Validator::make(
            $request->all(),
            [
                'email_notifications' => [
                    'nullable',
                    'boolean',
                ],

                'order_updates' => [
                    'nullable',
                    'boolean',
                ],

                'promotional_emails' => [
                    'nullable',
                    'boolean',
                ],

                'profile_visible' => [
                    'nullable',
                    'boolean',
                ],
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Validation Error
        |--------------------------------------------------------------------------
        */

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Please check the submitted settings.',
                        'errors' => $validator->errors(),
                    ],
                    422
                );
            }

            return back()
                ->withErrors($validator)
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Current User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | User Settings
        |--------------------------------------------------------------------------
        */

        $settings = UserSetting::firstOrCreate(
            ['user_id' => $user->id],
            [
                'email_notifications' => true,
                'order_updates' => true,
                'promotional_emails' => false,
                'profile_visible' => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Update Settings
        |--------------------------------------------------------------------------
        */

        $settings->update([
            'email_notifications' => $request->boolean(
                'email_notifications'
            ),

            'order_updates' => $request->boolean(
                'order_updates'
            ),

            'promotional_emails' => $request->boolean(
                'promotional_emails'
            ),

            'profile_visible' => $request->boolean(
                'profile_visible'
            ),
        ]);

        /*
        |--------------------------------------------------------------------------
        | AJAX Response
        |--------------------------------------------------------------------------
        */

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Your account preferences have been updated.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Normal Form Response
        |--------------------------------------------------------------------------
        */

        return back()->with(
            'success',
            'Your account preferences have been updated.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Password
    |--------------------------------------------------------------------------
    |
    | Current password yoxlanılır.
    | Sonra email-ə OTP göndərilir.
    | Yeni password Verify Code səhifəsində daxil edilir.
    |
    */

    public function updatePassword(
        Request $request
    ): RedirectResponse|JsonResponse {
        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        |
        | Burada artıq yalnız Current Password lazımdır.
        |
        */

        $validator = Validator::make(
            $request->all(),
            [
                'current_password' => [
                    'required',
                    'current_password',
                ],
            ],
            [
                'current_password.required' =>
                    'Current password is required.',

                'current_password.current_password' =>
                    'The current password is incorrect.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Validation Error
        |--------------------------------------------------------------------------
        */

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => $validator->errors()->first(),
                        'errors' => $validator->errors(),
                    ],
                    422
                );
            }

            return back()
                ->withErrors(
                    $validator,
                    'passwordUpdate'
                )
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Current User
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        $email = strtolower(
            trim($user->email)
        );

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
        | Store Password Change OTP
        |--------------------------------------------------------------------------
        */

        Otp::updateOrCreate(
            [
                'email' => $email,
                'purpose' => 'password_change',
            ],
            [
                'otp_code' => $otp,
                'expires_at' => Carbon::now()->addMinutes(10),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Store Sensitive Action
        |--------------------------------------------------------------------------
        |
        | Yeni password burada saxlanılmır.
        | User onu Verify Code səhifəsində daxil edəcək.
        |
        */

        session([
            'sensitive_action' => 'password_change',
            'sensitive_action_email' => $email,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Send OTP Email
        |--------------------------------------------------------------------------
        */

        try {
            Mail::raw(
                "Your SecondBook password change verification code is: {$otp}",
                function ($message) use ($user) {
                    $message
                        ->to($user->email)
                        ->subject(
                            'SecondBook Password Change Verification'
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
                    'password_change'
                )
                ->delete();

            session()->forget([
                'sensitive_action',
                'sensitive_action_email',
            ]);

            if ($request->expectsJson()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' =>
                            'Unable to send the verification code. Please try again.',
                    ],
                    500
                );
            }

            return back()
                ->withErrors([
                    'current_password' =>
                        'Unable to send the verification code. Please try again.',
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | AJAX Success
        |--------------------------------------------------------------------------
        */

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' =>
                    'Verification code sent successfully. Please check your email.',
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
}

