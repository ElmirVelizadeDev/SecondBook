<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Otp;
use App\Models\AccountSecurityEvent;
use App\Models\Refund;
use App\Models\UserSetting;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
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
                'message_updates' => true,
                'promotional_emails' => false,
                'profile_visible' => true,
            ]
        );

        $profileFields = [
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => 'Email address',
            'phone' => 'Phone number',
            'profile_photo' => 'Profile photo',
            'bio' => 'About you',
            'country' => 'Country',
            'city' => 'City',
        ];
        $missingProfileFields = collect($profileFields)
            ->filter(fn (string $label, string $field) => blank($user->{$field}))
            ->values();
        $profileCompletion = (int) round(
            ((count($profileFields) - $missingProfileFields->count()) / count($profileFields)) * 100
        );
        $accountDeletionBlocked = Refund::query()
            ->where('user_id', $user->id)
            ->orWhereIn('order_id', $user->orders()->select('id'))
            ->exists();

        return view(
            'Frontend.settings',
            compact('user', 'settings', 'profileCompletion', 'missingProfileFields', 'accountDeletionBlocked')
        );
    }

    public function securityEvents(Request $request): JsonResponse
    {
        $events = AccountSecurityEvent::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->limit(20)
            ->get(['id', 'event', 'ip_address', 'created_at']);

        return response()->json(['data' => $events]);
    }

    public function export(Request $request)
    {
        $userId = $request->user()->id;
        $filename = 'secondbook-account-' . now()->format('Ymd') . '.json';

        return response()->streamDownload(function () use ($userId) {
            $user = \App\Models\User::query()->findOrFail($userId);
            $settings = UserSetting::query()->where('user_id', $userId)->first();

            echo '{"exported_at":' . json_encode(now()->toIso8601String()) . ',"profile":';
            echo json_encode($user->only([
                'name', 'first_name', 'last_name', 'username', 'email', 'email_verified_at',
                'phone', 'profile_photo', 'date_of_birth', 'gender', 'country', 'city',
                'state', 'postal_code', 'address', 'bio', 'created_at', 'updated_at',
            ]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            echo ',"preferences":' . json_encode($settings?->only([
                'email_notifications', 'order_updates', 'message_updates', 'promotional_emails', 'profile_visible',
            ]), JSON_UNESCAPED_UNICODE);
            echo ',"orders":[';
            $first = true;
            foreach ($user->orders()->select([
                'id', 'order_number', 'book_id', 'book_price', 'quantity', 'total_price',
                'payment_method', 'payment_status', 'order_status', 'full_name', 'phone',
                'country', 'city', 'postal_code', 'address', 'note', 'created_at', 'updated_at',
            ])->orderBy('id')->cursor() as $order) {
                echo ($first ? '' : ',') . json_encode($order, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                $first = false;
            }
            echo '],"reviews":[';
            $first = true;
            foreach ($user->reviews()->select([
                'id', 'book_id', 'rating', 'comment', 'status', 'created_at', 'updated_at',
            ])->orderBy('id')->cursor() as $review) {
                echo ($first ? '' : ',') . json_encode($review, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                $first = false;
            }
            echo '],"security_events":';
            echo json_encode(AccountSecurityEvent::query()
                ->where('user_id', $userId)
                ->latest()
                ->limit(20)
                ->get(['event', 'ip_address', 'created_at']), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            echo '}';
        }, $filename, [
            'Content-Type' => 'application/json; charset=UTF-8',
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function sessions(Request $request): JsonResponse
    {
        if (config('session.driver') !== 'database'
            || !Schema::hasTable(config('session.table', 'sessions'))
        ) {
            return response()->json([
                'supported' => false,
                'message' => 'This installation does not store sessions in a manageable database table.',
                'data' => [],
            ]);
        }

        $currentSessionId = $request->session()->getId();
        $sessions = DB::table(config('session.table', 'sessions'))
            ->where('user_id', $request->user()->id)
            ->where('last_activity', '>=', now()->subMinutes((int) config('session.lifetime', 120))->timestamp)
            ->orderByDesc('last_activity')
            ->get(['id', 'ip_address', 'user_agent', 'last_activity'])
            ->map(fn ($session) => [
                'key' => hash('sha256', $session->id),
                'current' => hash_equals((string) $currentSessionId, (string) $session->id),
                'ip_address' => $session->ip_address,
                'user_agent' => $session->user_agent,
                'last_activity' => Carbon::createFromTimestamp($session->last_activity)->toIso8601String(),
            ]);

        return response()->json(['supported' => true, 'data' => $sessions]);
    }

    public function revokeSession(Request $request, string $sessionKey): JsonResponse
    {
        abort_unless(preg_match('/^[a-f0-9]{64}$/', $sessionKey) === 1, 404);

        if (config('session.driver') !== 'database'
            || !Schema::hasTable(config('session.table', 'sessions'))
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Session management is not available for this session driver.',
            ], 501);
        }

        $currentSessionId = $request->session()->getId();
        $session = DB::table(config('session.table', 'sessions'))
            ->where('user_id', $request->user()->id)
            ->get(['id'])
            ->first(fn ($row) => hash_equals($sessionKey, hash('sha256', $row->id)));

        abort_unless($session, 404);
        abort_if(hash_equals((string) $currentSessionId, (string) $session->id), 422, 'The current session cannot be revoked here.');

        DB::table(config('session.table', 'sessions'))
            ->where('id', $session->id)
            ->where('user_id', $request->user()->id)
            ->delete();

        return response()->json(['success' => true, 'message' => 'The selected session was signed out.']);
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

                'message_updates' => [
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

            'message_updates' => $request->boolean(
                'message_updates'
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

