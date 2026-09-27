<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Otp;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Throwable;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register
    |--------------------------------------------------------------------------
    */

    public function register()
    {
        return view('auth.register');
    }

    public function emailVerify()
    {
        $email = session('email_verification_email');

        if (!$email) {
            return redirect()->route('frontend.auth.login');
        }

        return view('auth.email-verify', compact('email'));
    }

    public function verifyEmailOtp(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Get Pending Registration
        |--------------------------------------------------------------------------
        */

        $pendingRegistration = session('pending_registration');

        $email = session('email_verification_email');

        /*
        |--------------------------------------------------------------------------
        | Verification Session Check
        |--------------------------------------------------------------------------
        */

        if (!$pendingRegistration || !$email) {
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
                'email_verification'
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

    public function storeRegister(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Check Registration Status
        |--------------------------------------------------------------------------
        */

        if (!Setting::get('user_registration_enabled', true)) {
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

    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    public function login()
    {
        return view('auth.login');
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
                    'message' =>
                        $validator->errors()->first(),
                    'errors' =>
                        $validator->errors(),
                ], 422);
            }

            return back()
                ->withErrors($validator)
                ->withInput(
                    $request->only(
                        'email',
                        'remember'
                    )
                );
        }

        $credentials = $validator->validated();

        $remember = $request->boolean('remember');

        $email = trim(
            strtolower($credentials['email'])
        );

        $password = $credentials['password'];

        $user = User::whereRaw(
            'LOWER(email) = ?',
            [$email]
        )->first();

        if ($user) {
            $passwordValid = false;

            /*
            |--------------------------------------------------------------------------
            | Check Hashed Password
            |--------------------------------------------------------------------------
            */

            if (
                Hash::check(
                    $password,
                    $user->password
                )
            ) {
                $passwordValid = true;
            } elseif (
                $user->password &&
                $password === $user->password
            ) {
                /*
                |--------------------------------------------------------------------------
                | Legacy Plaintext Password Support
                |--------------------------------------------------------------------------
                */

                $passwordValid = true;

                $user->password =
                    Hash::make($password);

                $user->save();
            }

            if ($passwordValid) {
                /*
                |--------------------------------------------------------------------------
                | Login
                |--------------------------------------------------------------------------
                */

                if (is_null($user->email_verified_at)) {
                    session([
                        'email_verification_email' => strtolower($user->email),
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Please verify your email address before signing in.',
                        'redirect' => route('frontend.auth.email.verify'),
                    ], 403);
                }

                Auth::login(
                    $user,
                    $remember
                );

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
                | Redirect By Role
                |--------------------------------------------------------------------------
                */

                $role = strtolower(
                    (string) ($user->role ?? '')
                );

                if (
                    in_array(
                        $role,
                        [
                            'admin',
                            'superadmin',
                            'administrator',
                        ],
                        true
                    )
                ) {
                    $redirectUrl =
                        route('admin.dashboard');
                } else {
                    $redirectUrl =
                        route('frontend.home');
                }

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

    /*
    |--------------------------------------------------------------------------
    | Google Login
    |--------------------------------------------------------------------------
    */

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

            Auth::login($user , true);

            $request->session()->regenerate();

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

    /*
    |--------------------------------------------------------------------------
    | Password Request
    |--------------------------------------------------------------------------
    */

    public function passwordRequest()
    {
        return view(
            'auth.password-request'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Send OTP - Password Reset
    |--------------------------------------------------------------------------
    */

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
                    ],
                    true
                ) &&
                $sensitiveEmail === strtolower($authenticatedUser->email)
            ) {
                $purpose = $sensitiveAction;

                $email =
                    strtolower(
                        $authenticatedUser->email
                    );
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

    /*
    |--------------------------------------------------------------------------
    | Verify OTP
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route(
                'frontend.auth.login'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | My Profile
    |--------------------------------------------------------------------------
    */

    public function myprofile()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Account Statistics
        |--------------------------------------------------------------------------
        */

        $orderCount =
            $user->orders()->count();

        $wishlistCount =
            $user->wishlists()->count();

        $reviewsCount =
            $user->reviews()->count();

        /*
        |--------------------------------------------------------------------------
        | Books Sold
        |--------------------------------------------------------------------------
        */

        $booksSoldCount = 0;

        if ($user->isSeller()) {
            $booksSoldCount = Order::whereHas(
                'book',
                function ($query) use ($user) {
                    $query->where(
                        'seller_id',
                        $user->id
                    );
                }
            )
                ->whereIn(
                    'order_status',
                    [
                        'processing',
                        'shipped',
                        'delivered',
                    ]
                )
                ->sum('quantity');
        }

        /*
        |--------------------------------------------------------------------------
        | Profile Data
        |--------------------------------------------------------------------------
        */

        $profileName = trim(
            $user->first_name . ' ' .
            $user->last_name
        );

        if ($profileName === '') {
            $profileName =
                $user->name ?: 'SecondBook User';
        }

        $profileRole = match ($user->role) {
            'seller' =>
                'Seller',

            'admin' =>
                'Administrator',

            default =>
                'User',
        };

        $profileStatus = match ($user->status) {
            'active' => [
                'label' =>
                    'Active',

                'description' =>
                    'Your account is active and ready to use.',

                'class' =>
                    'active',

                'icon' =>
                    'bi-check-lg',
            ],

            'inactive' => [
                'label' =>
                    'Inactive',

                'description' =>
                    'Your account is currently inactive.',

                'class' =>
                    'inactive',

                'icon' =>
                    'bi-pause-lg',
            ],

            'banned' => [
                'label' =>
                    'Banned',

                'description' =>
                    'Your account is currently restricted.',

                'class' =>
                    'banned',

                'icon' =>
                    'bi-slash-circle',
            ],

            default => [
                'label' =>
                    ucfirst(
                        $user->status ?? 'Unknown'
                    ),

                'description' =>
                    'Your current account status.',

                'class' =>
                    'unknown',

                'icon' =>
                    'bi-info-lg',
            ],
        };

        /*
        |--------------------------------------------------------------------------
        | Location / Address
        |--------------------------------------------------------------------------
        */

        $addressParts = array_filter([
            $user->address,
            $user->city,
            $user->state,
            $user->postal_code,
            $user->country,
        ]);

        $profileAddress =
            !empty($addressParts)
                ? implode(
                    ', ',
                    $addressParts
                )
                : 'Not provided';

        /*
        |--------------------------------------------------------------------------
        | Dates
        |--------------------------------------------------------------------------
        */

        $memberSince = $user->created_at
            ? $user->created_at->format('F Y')
            : 'N/A';

        $joinedDate = $user->created_at
            ? $user->created_at->format('F j, Y')
            : 'N/A';

        $lastAccountUpdate =
            $user->updated_at
                ? $user->updated_at->format('F j, Y')
                : 'N/A';

        $lastLogin = $user->last_login_at
            ? $user->last_login_at->format(
                'F j, Y \a\t g\:i A'
            )
            : 'Not available';

        /*
        |--------------------------------------------------------------------------
        | Avatar
        |--------------------------------------------------------------------------
        */

        $avatarInitials = '';

        $nameParts = array_filter(
            preg_split(
                '/\s+/',
                trim($profileName)
            ) ?: []
        );

        foreach (
            array_slice(
                $nameParts,
                0,
                2
            ) as $part
        ) {
            $avatarInitials .= mb_strtoupper(
                mb_substr(
                    $part,
                    0,
                    1
                )
            );
        }

        if ($avatarInitials === '') {
            $avatarInitials = 'SB';
        }

        /*
        |--------------------------------------------------------------------------
        | Seller Store
        |--------------------------------------------------------------------------
        */

        $store = null;

        if ($user->isSeller()) {
            $store = $user->store;
        }

        return view(
            'Auth.my-profile',
            compact(
                'user',
                'profileName',
                'profileRole',
                'profileStatus',
                'profileAddress',
                'memberSince',
                'joinedDate',
                'lastAccountUpdate',
                'lastLogin',
                'avatarInitials',
                'orderCount',
                'wishlistCount',
                'booksSoldCount',
                'reviewsCount',
                'store'
            )
        );
    }

    public function editProfile()
    {
        $user = Auth::user();

        return view(
            'Auth.edit-profile',
            compact('user')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Profile
    |--------------------------------------------------------------------------
    */

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Normalize Name & Email
        |--------------------------------------------------------------------------
        */

        $request->merge([
            'first_name' => trim(
                (string) $request->first_name
            ),

            'last_name' => trim(
                (string) $request->last_name
            ),

            'email' => strtolower(
                trim(
                    (string) $request->email
                )
            ),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Profile Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validateWithBag(
            'profileUpdate',
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
                    'nullable',
                    'string',
                    'min:3',
                    'max:100',
                    'regex:/^[A-Za-z0-9\_.-]+$/',
                    Rule::unique(
                        'users',
                        'username'
                    )->ignore($user->id),
                ],

                'email' => [
                    'required',
                    'string',
                    'email:rfc',
                    'max:255',
                    Rule::unique(
                        'users',
                        'email'
                    )->ignore($user->id),
                ],

                'phone_country_code' => [
                    'required',
                    'string',
                    Rule::in([
                        '+994',
                        '+90',
                        '+7',
                        '+380',
                        '+49',
                        '+33',
                        '+44',
                        '+39',
                        '+34',
                        '+1',
                    ]),
                ],

                'phone' => [
                    'nullable',
                    'string',
                    'regex:/^[0-9]+$/',
                ],

                'date_of_birth' => [
                    'nullable',
                    'date',
                    'before_or_equal:today',
                ],

                'gender' => [
                    'nullable',
                    Rule::in([
                        'male',
                        'female',
                        'other',
                        'prefer_not_to_say',
                    ]),
                ],

                'country' => [
                    'nullable',
                    'string',
                    'max:120',
                ],

                'city' => [
                    'nullable',
                    'string',
                    'max:120',
                ],

                'state' => [
                    'nullable',
                    'string',
                    'max:120',
                ],

                'postal_code' => [
                    'nullable',
                    'string',
                    'max:30',
                ],

                'address' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'bio' => [
                    'nullable',
                    'string',
                    'max:1000',
                ],

                'profile_photo' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:2048',
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


                'username.min' =>
                    'Username must be at least 3 characters.',

                'username.max' =>
                    'Username may not exceed 100 characters.',

                'username.regex' =>
                    'Username may contain letters, numbers, dots, underscores, and hyphens only.',

                'username.unique' =>
                    'This username is already taken.',


                'email.required' =>
                    'Email address is required.',

                'email.email' =>
                    'Please enter a valid email address.',

                'email.max' =>
                    'Email address may not exceed 255 characters.',

                'email.unique' =>
                    'This email is already registered.',


                'phone_country_code.required' =>
                    'Please select a country code.',

                'phone_country_code.in' =>
                    'Please select a valid country code.',

                'phone.regex' =>
                    'Phone number may contain numbers only.',


                'date_of_birth.date' =>
                    'Please enter a valid date of birth.',

                'date_of_birth.before_or_equal' =>
                    'Date of birth cannot be in the future.',


                'profile_photo.image' =>
                    'The profile photo must be a valid image.',

                'profile_photo.mimes' =>
                    'Profile photo must be JPG, JPEG, PNG, or WEBP.',

                'profile_photo.max' =>
                    'Profile photo may not exceed 2 MB.',


                'bio.max' =>
                    'Bio may not exceed 1000 characters.',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Profile Photo
        |--------------------------------------------------------------------------
        */

        $profilePhotoPath =
            $user->profile_photo ?? null;

        if ($request->hasFile('profile_photo')) {

            if ($profilePhotoPath) {

                Storage::disk('public')->delete(
                    $profilePhotoPath
                );
            }

            $profilePhotoPath =
                $request
                    ->file('profile_photo')
                    ->store(
                        'profile-photos',
                        'public'
                    );
        }

        /*
        |--------------------------------------------------------------------------
        | Name
        |--------------------------------------------------------------------------
        */

        $firstName = trim(
            (string) $validated['first_name']
        );

        $lastName = trim(
            (string) $validated['last_name']
        );

        $fullName = trim(
            $firstName . ' ' . $lastName
        );

        /*
        |--------------------------------------------------------------------------
        | Username
        |--------------------------------------------------------------------------
        */

        $username =
            $validated['username']
            ?? $user->username;

        /*
        |--------------------------------------------------------------------------
        | Phone Number
        |--------------------------------------------------------------------------
        */

        $phoneNumber = preg_replace(
            '/\D/',
            '',
            $validated['phone'] ?? ''
        );

        /*
        |--------------------------------------------------------------------------
        | Phone Rules By Country
        |--------------------------------------------------------------------------
        */

        $phoneRules = [

            '+994' => [
                'min' => 9,
                'max' => 9,
            ],

            '+90' => [
                'min' => 10,
                'max' => 10,
            ],

            '+7' => [
                'min' => 10,
                'max' => 10,
            ],

            '+380' => [
                'min' => 9,
                'max' => 9,
            ],

            '+49' => [
                'min' => 7,
                'max' => 12,
            ],

            '+33' => [
                'min' => 9,
                'max' => 9,
            ],

            '+44' => [
                'min' => 9,
                'max' => 10,
            ],

            '+39' => [
                'min' => 9,
                'max' => 10,
            ],

            '+34' => [
                'min' => 9,
                'max' => 9,
            ],

            '+1' => [
                'min' => 10,
                'max' => 10,
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Validate Phone Length
        |--------------------------------------------------------------------------
        */

        if ($phoneNumber !== '') {

            $countryCode =
                $validated['phone_country_code'];

            $minLength =
                $phoneRules[$countryCode]['min'];

            $maxLength =
                $phoneRules[$countryCode]['max'];

            $phoneLength =
                strlen($phoneNumber);

            if (
                $phoneLength < $minLength ||
                $phoneLength > $maxLength
            ) {

                $message =
                    "Please enter a valid phone number for {$countryCode}.";

                /*
                |--------------------------------------------------------------------------
                | AJAX Validation Error
                |--------------------------------------------------------------------------
                */

                if ($request->expectsJson()) {

                    return response()->json([
                        'success' => false,

                        'message' => $message,

                        'errors' => [
                            'phone' => [
                                $message,
                            ],
                        ],
                    ], 422);
                }

                /*
                |--------------------------------------------------------------------------
                | Normal Request Validation Error
                |--------------------------------------------------------------------------
                */

                return back()
                    ->withErrors([
                        'phone' => $message,
                    ])
                    ->withInput();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Full Phone Number
        |--------------------------------------------------------------------------
        */

        $fullPhone =
            $phoneNumber !== ''
                ? $validated['phone_country_code'] .
                    $phoneNumber
                : null;

        /*
        |--------------------------------------------------------------------------
        | Update User
        |--------------------------------------------------------------------------
        */

        $user->update([

            'first_name' =>
                $firstName,

            'last_name' =>
                $lastName,

            'name' =>
                $fullName,

            'username' =>
                $username,

            'email' =>
                strtolower(
                    trim(
                        $validated['email']
                    )
                ),

            'phone' =>
                $fullPhone,

            'date_of_birth' =>
                $validated['date_of_birth'] ?? null,

            'gender' =>
                $validated['gender'] ?? null,

            'country' =>
                $validated['country'] ?? null,

            'city' =>
                $validated['city'] ?? null,

            'state' =>
                $validated['state'] ?? null,

            'postal_code' =>
                $validated['postal_code'] ?? null,

            'address' =>
                $validated['address'] ?? null,

            'bio' =>
                $validated['bio'] ?? null,

            'profile_photo' =>
                $profilePhotoPath,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Refresh User
        |--------------------------------------------------------------------------
        |
        | Make sure the JSON response contains the latest database values.
        |
        */

        $user->refresh();

        /*
        |--------------------------------------------------------------------------
        | AJAX Success Response
        |--------------------------------------------------------------------------
        */

        if ($request->expectsJson()) {

            return response()->json([

                'success' => true,

                'message' =>
                    'Profile updated successfully.',

                'user' => [

                    'full_name' =>
                        trim(
                            ($user->first_name ?? '') .
                            ' ' .
                            ($user->last_name ?? '')
                        ),

                    'email' =>
                        $user->email,

                    'role' =>
                        ucfirst(
                            $user->role ?? 'User'
                        ),

                    'profile_photo_url' =>
                        $user->profile_photo
                            ? asset(
                                'storage/' .
                                $user->profile_photo
                            )
                            : null,
                ],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Normal Request Success
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('profile.edit')
            ->with(
                'success',
                'Profile updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Remove Profile Photo
    |--------------------------------------------------------------------------
    */

    public function removeProfilePhoto()
    {
        $user = Auth::user();

        if ($user->profile_photo) {
            Storage::disk('public')->delete(
                $user->profile_photo
            );

            $user->update([
                'profile_photo' => null,
            ]);
        }

        return redirect()
            ->route('profile.edit')
            ->with(
                'success',
                'Profile photo removed successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Password
    |--------------------------------------------------------------------------
    |
    | Password changes from Account Settings are handled by
    | AccountSettingsController. This method is kept for compatibility
    | with any existing route that may still use it.
    |--------------------------------------------------------------------------
    */

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $minimumPasswordLength = Setting::get(
            'minimum_password_length',
            8
        );

        $validated = $request->validateWithBag(
            'passwordUpdate',
            [
                'current_password' => [
                    'required',
                    'current_password',
                ],

                'password' => [
                    'required',
                    'string',
                    'min:' . $minimumPasswordLength,
                    'max:128',
                    'regex:/[a-z]/',
                    'regex:/[0-9]/',
                    'confirmed',
                    'different:current_password',
                ],
            ],

            [
                'current_password.required' =>
                    'Current password is required.',

                'current_password.current_password' =>
                    'The current password is incorrect.',

                'password.required' =>
                    'New password is required.',

                'password.min' =>
                    'Password must be at least ' .
                    $minimumPasswordLength .
                    ' characters.',

                'password.max' =>
                    'Password must not exceed 128 characters.',

                'password.regex' =>
                    'Password must contain at least one lowercase letter and one number.',

                'password.confirmed' =>
                    'Password confirmation does not match.',

                'password.different' =>
                    'New password must be different from your current password.',
            ]
        );

        $user->update([
            'password' =>
                Hash::make(
                    $validated['password']
                ),
        ]);

        return redirect()
            ->route('profile.edit')
            ->with(
                'success',
                'Password updated successfully.'
            );
    }

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

        return view('auth.account-delete-verify');
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

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Your session has expired. Please log in again.',
            ], 401);
        }

        if (
            !session('account_delete_password_verified')
        ) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Please verify your current password first.',
            ], 422);
        }

        $email = strtolower(
            trim($user->email)
        );

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

        $otp = Otp::where(
            'email',
            $email
        )
            ->where(
                'purpose',
                'account_delete'
            )
            ->where(
                'otp_code',
                $request->otp_code
            )
            ->first();

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
        | Delete OTP
        |--------------------------------------------------------------------------
        */

        $otp->delete();

        /*
        |--------------------------------------------------------------------------
        | Logout
        |--------------------------------------------------------------------------
        */

        Auth::logout();

        /*
        |--------------------------------------------------------------------------
        | Delete Account
        |--------------------------------------------------------------------------
        */

        $user->delete();

        /*
        |--------------------------------------------------------------------------
        | Clear Session
        |--------------------------------------------------------------------------
        */

        session()->forget([
            'sensitive_action',
            'sensitive_action_email',
            'account_delete_password_verified',
        ]);

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,

            'message' =>
                'Your account has been deleted.',

            'redirect' =>
                route('frontend.home'),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Destroy Profile
    |--------------------------------------------------------------------------
    |
    | Account is NOT deleted immediately.
    | A verification code is sent to the user's email first.
    |--------------------------------------------------------------------------
    */

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

