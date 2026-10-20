<?php

namespace App\Http\Controllers\Auth;

use Throwable;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class VerificationCodeController extends Controller
{
    public function resendVerificationCode(Request $request)
    {
        $sensitiveAction = session('sensitive_action');
        $sensitiveEmail = session('sensitive_action_email');

        $authenticatedUser = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Registration Email Verification
        |--------------------------------------------------------------------------
        */

        $pendingRegistration =
            session('pending_registration');

        $emailVerificationEmail =
            session('email_verification_email');

        if (
            $pendingRegistration &&
            $emailVerificationEmail
        ) {
            $purpose = 'email_verification';

            $email = strtolower(
                trim(
                    $emailVerificationEmail
                )
            );
        } else {
            /*
            |--------------------------------------------------------------------------
            | Existing Sensitive Actions
            |--------------------------------------------------------------------------
            */

            $authenticatedUser = Auth::user();

            if (
                $authenticatedUser &&
                in_array(
                    $sensitiveAction,
                    [
                        'password_change',
                        'account_delete',
                        'email_change',
                    ],
                    true
                ) &&
                $sensitiveEmail === strtolower($authenticatedUser->email)
            ) {
                $purpose = $sensitiveAction;

                $email = $sensitiveAction === 'email_change'
                    ? strtolower((string) session('pending_email'))
                    : strtolower($authenticatedUser->email);
            } else {
                $purpose = 'password_reset';

                $email =
                    strtolower(
                        (string) session('reset_email')
                    );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Verification Session
        |--------------------------------------------------------------------------
        */

        if (!$email) {
            return response()->json([
                'success' => false,
                'message' => 'Your verification session has expired. Please start again.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Password Reset User Check
        |--------------------------------------------------------------------------
        */

        if ($purpose === 'password_reset') {
            $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'No account was found with this email address.',
                ], 422);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Generate New OTP
        |--------------------------------------------------------------------------
        */

        $otp = (string) random_int(100000, 999999);

        Otp::updateOrCreate(
            [
                'email' => $email,
                'purpose' => $purpose,
            ],
            [
                'otp_code' => $otp,
                'expires_at' => Carbon::now()->addMinutes(10),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Email Content
        |--------------------------------------------------------------------------
        */

        if ($purpose === 'account_delete') {
            $subject = 'SecondBook Account Deletion Verification';

            $message = "Your SecondBook account deletion verification code is: {$otp}\n\n"
                . "This code will expire in 10 minutes.";
        } elseif ($purpose === 'password_change') {
            $subject = 'SecondBook Password Change Verification';

            $message = "Your SecondBook password change verification code is: {$otp}\n\n"
                . "This code will expire in 10 minutes.";
        } elseif ($purpose === 'email_change') {
            $subject = 'SecondBook Email Change Verification';

            $message = "Your SecondBook email change verification code is: {$otp}\n\n"
                . "This code will expire in 10 minutes.";
        } else {
            $subject = 'SecondBook Password Reset OTP';

            $message = "Your SecondBook password reset verification code is: {$otp}\n\n"
                . "This code will expire in 10 minutes.";
        }

        /*
        |--------------------------------------------------------------------------
        | Send Email
        |--------------------------------------------------------------------------
        */

        try {
            Mail::raw($message, function ($mail) use ($email, $subject) {
                $mail->to($email)
                    ->subject($subject);
            });
        } catch (Throwable $e) {
            Otp::where('email', $email)
                ->where('purpose', $purpose)
                ->delete();

            return response()->json([
                'success' => false,
                'message' => 'Unable to send the verification code right now. Please try again.',
            ], 500);
        }

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return response()->json([
            'success' => true,
            'message' => 'A new verification code has been sent to your email.',
        ]);
    }
}
