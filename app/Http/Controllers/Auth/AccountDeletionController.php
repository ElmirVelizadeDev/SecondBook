<?php

namespace App\Http\Controllers\Auth;

use Throwable;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\Refund;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AccountDeletionController extends Controller
{
    public function accountDeleteVerify()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()
                ->route('frontend.auth.login');
        }

        $action = session('sensitive_action');
        $email = session('sensitive_action_email');

        if (
            $action !== 'account_delete' ||
            !$email ||
            strtolower($email) !== strtolower($user->email)
        ) {
            return redirect()
                ->route('frontend.account.settings')
                ->with(
                    'error',
                    'Your account deletion verification session has expired.'
                );
        }

        return view('Auth.account-delete-verify');
    }

    public function verifyAccountDeletePassword(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Your session has expired. Please log in again.',
            ], 401);
        }

        $action = session('sensitive_action');
        $email = session('sensitive_action_email');

        if (
            $action !== 'account_delete' ||
            !$email ||
            strtolower($email) !== strtolower($user->email)
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Your account deletion verification session has expired.',
            ], 422);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'current_password' => [
                    'required',
                    'string',
                ],
            ],
            [
                'current_password.required' =>
                    'Current password is required.',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        if (
            !Hash::check(
                $request->current_password,
                $user->password
            )
        ) {
            return response()->json([
                'success' => false,
                'message' => 'The current password is incorrect.',
                'errors' => [
                    'current_password' => [
                        'The current password is incorrect.',
                    ],
                ],
            ], 422);
        }

        session([
            'account_delete_password_verified' => true,
        ]);

        return response()->json([
            'success' => true,
            'step' => 'otp',
        ]);
    }

    public function verifyAccountDeleteOtp(Request $request)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Authentication Check
        |--------------------------------------------------------------------------
        */

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Your session has expired. Please log in again.',
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Verify Sensitive Action Session
        |--------------------------------------------------------------------------
        */

        $action = session('sensitive_action');
        $sensitiveEmail = session('sensitive_action_email');

        if (
            $action !== 'account_delete' ||
            !$sensitiveEmail ||
            strtolower(trim($sensitiveEmail)) !== strtolower(trim($user->email))
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Your account deletion verification session has expired. Please start again.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Verify Password Was Confirmed
        |--------------------------------------------------------------------------
        */

        if (!session('account_delete_password_verified')) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Please verify your current password first.',
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
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Get User Email
        |--------------------------------------------------------------------------
        */

        $email = strtolower(trim($user->email));

        /*
        |--------------------------------------------------------------------------
        | Find OTP
        |--------------------------------------------------------------------------
        */

        $otp = Otp::where('email', $email)
            ->where('purpose', 'account_delete')
            ->where('otp_code', trim($request->otp_code))
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

        if (Carbon::now()->greaterThan($otp->expires_at)) {

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

        $deleted = DB::transaction(function () use ($user, $otp): bool {
            if (Refund::query()
                ->where('user_id', $user->id)
                ->orWhereIn('order_id', $user->orders()->select('id'))
                ->exists()
            ) {
                return false;
            }

            $otp->delete();
            $user->delete();

            return true;
        });

        if (!$deleted) {
            return response()->json([
                'success' => false,
                'message' => 'Account deletion is blocked while refund records are linked to this account or its orders. Please contact support.',
            ], 409);
        }

        /*
        |--------------------------------------------------------------------------
        | Delete Profile Photo
        |--------------------------------------------------------------------------
        */

        if ($user->profile_photo) {
            Storage::disk('public')->delete(
                $user->profile_photo
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Logout
        |--------------------------------------------------------------------------
        */

        Auth::logout();

        /*
        |--------------------------------------------------------------------------
        | Clear Sensitive Session Data
        |--------------------------------------------------------------------------
        */

        session()->forget([
            'sensitive_action',
            'sensitive_action_email',
            'account_delete_password_verified',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Invalidate Session
        |--------------------------------------------------------------------------
        */

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        /*
        |--------------------------------------------------------------------------
        | Success Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,
            'message' => 'Your account has been deleted.',
            'redirect' => route('frontend.auth.login'),
        ]);
    }

    public function destroyProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' =>
                        'You must be logged in to delete your account.',
                ], 401);
            }

            return redirect()
                ->route('frontend.auth.login');
        }

        if (Refund::query()
            ->where('user_id', $user->id)
            ->orWhereIn('order_id', $user->orders()->select('id'))
            ->exists()
        ) {
            $message = 'Account deletion is blocked while refund records are linked to this account or its orders. Please contact support.';

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 409);
            }

            return back()->withErrors(['delete_account' => $message]);
        }

        $email = strtolower(
            trim($user->email)
        );

        /*
        |--------------------------------------------------------------------------
        | Reset Delete Verification State
        |--------------------------------------------------------------------------
        */

        session()->forget([
            'account_delete_password_verified',
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
        | Store Account Delete OTP
        |--------------------------------------------------------------------------
        */

        Otp::updateOrCreate(
            [
                'email' =>
                    $email,

                'purpose' =>
                    'account_delete',
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
        | Store Sensitive Action
        |--------------------------------------------------------------------------
        */

        session([
            'sensitive_action' =>
                'account_delete',

            'sensitive_action_email' =>
                $email,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Send Email
        |--------------------------------------------------------------------------
        */

        try {
            Mail::raw(
                "Your SecondBook account deletion verification code is: {$otp}\n\n"
                . "This code will expire in 10 minutes.",

                function ($message) use ($email) {
                    $message
                        ->to($email)
                        ->subject(
                            'SecondBook Account Deletion Verification'
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
                    'account_delete'
                )
                ->delete();

            session()->forget([
                'sensitive_action',
                'sensitive_action_email',
                'account_delete_password_verified',
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
                    'delete_account' =>
                        'Unable to send the verification code. Please try again.',
                ]);
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
                        'frontend.auth.account.delete.verify'
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
                'frontend.auth.account.delete.verify'
            )
            ->with(
                'status',
                'Please verify your password and enter the code sent to your email.'
            );
    }
}
