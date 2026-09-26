<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PasswordOtp;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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

    public function storeRegister(Request $request)
    {
        // Check if user registration is enabled from Admin Settings
        if (!Setting::get('user_registration_enabled', true)) {
            return back()->with(
                'error',
                'User registration is currently disabled.'
            );
        }

        $request->merge([
            'first_name' => trim((string) $request->first_name),
            'last_name' => trim((string) $request->last_name),
            'username' => trim((string) $request->username),
            'email' => strtolower(trim((string) $request->email)),
        ]);

        $request->validate(
            [
                'first_name' => [
                    'required',
                    'string',
                    'min:2',
                    'max:50',
                    'regex:/^[\pL\pM]+(?:[\'-][\pL\pM]+)*$/u',
                ],

                'last_name' => [
                    'required',
                    'string',
                    'min:2',
                    'max:50',
                    'regex:/^[\pL\pM]+(?:[\'-][\pL\pM]+)*$/u',
                ],

                'username' => [
                    'required',
                    'string',
                    'min:3',
                    'max:100',
                    'regex:/^[A-Za-z0-9_.-]+$/',
                    'unique:users,username',
                ],

                'email' => [
                    'required',
                    'string',
                    'email:rfc',
                    'max:255',
                    'unique:users,email',
                ],

                'password' => [
                    'required',
                    'string',
                    'min:' . Setting::get(
                        'minimum_password_length',
                        8
                    ),
                    'confirmed',
                ],

                'terms' => 'accepted',
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

                'email.required' =>
                    'Email address is required.',

                'email.email' =>
                    'Please enter a valid email address.',

                'email.max' =>
                    'Email address may not exceed 255 characters.',

                'email.unique' =>
                    'This email is already registered.',

                'password.required' =>
                    'Password is required.',

                'password.min' =>
                    'Password does not meet the minimum length requirement.',

                'password.confirmed' =>
                    'Password confirmation does not match.',

                'terms.accepted' =>
                    'You must accept the Terms and Conditions.',
            ]
        );

        $user = User::create([
            'name' =>
                $request->first_name . ' ' . $request->last_name,

            'first_name' =>
                $request->first_name,

            'last_name' =>
                $request->last_name,

            'username' =>
                $request->username,

            'email' =>
                $request->email,

            'password' =>
                Hash::make($request->password),

            'role' =>
                'user',
        ]);

        Auth::login($user);

        return redirect()
            ->route('frontend.home');
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
        $credentials = $request->validate(
            [
                'email' => [
                    'required',
                    'string',
                    'email:rfc',
                    'max:255',
                ],

                'password' => [
                    'required',
                ],
            ],
            [
                'email.required' =>
                    'Email address is required.',

                'email.email' =>
                    'Please enter a valid email address.',

                'email.max' =>
                    'Email address may not exceed 255 characters.',

                'password.required' =>
                    'Password is required.',
            ]
        );

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

            if (Hash::check($password, $user->password)) {
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

                $user->password = Hash::make($password);

                $user->save();
            }

            if ($passwordValid) {
                /*
                |--------------------------------------------------------------------------
                | Login
                |--------------------------------------------------------------------------
                */

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
                    return redirect()
                        ->route('admin.dashboard');
                }

                return redirect()
                    ->route('frontend.home');
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Invalid Login
        |--------------------------------------------------------------------------
        */

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

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $email = strtolower(trim($googleUser->getEmail()));

            $user = User::whereRaw(
                'LOWER(email) = ?',
                [$email]
            )->first();

            if (!$user) {
                $name = trim($googleUser->getName() ?? '');

                $nameParts = preg_split(
                    '/\s+/',
                    $name,
                    2
                );

                $firstName = $nameParts[0] ?? 'Google';
                $lastName = $nameParts[1] ?? 'User';

                $baseUsername = strtolower(
                    preg_replace(
                        '/[^A-Za-z0-9_.-]/',
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
                    User::where('username', $username)->exists()
                ) {
                    $username =
                        $baseUsername . $counter;

                    $counter++;
                }

                $user = User::create([
                    'name' => $firstName . ' ' . $lastName,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'username' => $username,
                    'email' => $email,
                    'password' => Hash::make(
                        bin2hex(random_bytes(32))
                    ),
                    'role' => 'user',
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]);
            }

            Auth::login($user);

            $request->session()->regenerate();

            $user->update([
                'last_login_at' => now(),
            ]);

            if ($user->isAdmin()) {
                return redirect()->route(
                    'admin.dashboard'
                );
            }

            return redirect()->route(
                'frontend.home'
            );

        } catch (Throwable $e) {
            report($e);

            return redirect()
                ->route('frontend.auth.login')
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
        return view('auth.password-request');
    }

    public function sendOtp(Request $request)
    {
        $request->merge([
            'email' => strtolower(
                trim((string) $request->email)
            ),
        ]);

        $request->validate([
            'email' => [
                'required',
                'string',
                'email:rfc',
                'max:255',
            ],
        ]);

        $otp = rand(
            100000,
            999999
        );

        PasswordOtp::updateOrCreate(
            [
                'email' => $request->email,
            ],
            [
                'otp_code' => $otp,
                'expires_at' => Carbon::now()->addMinutes(10),
            ]
        );

        try {
            Mail::raw(
                "Your SecondBook password reset code is: $otp",
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
        }

        // Emaili session-da saxlayırıq
        session([
            'reset_email' => $request->email,
        ]);

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
        $request->validate([
            'otp_code' => [
                'required',
                'digits:6',
            ],

            'password' => [
                'required',
                'string',
                'min:' . Setting::get(
                    'minimum_password_length',
                    8
                ),
            ],
        ]);

        $otp = PasswordOtp::where(
            'email',
            session('reset_email')
        )
            ->where(
                'otp_code',
                $request->otp_code
            )
            ->first();

        if (!$otp) {
            return back()
                ->withErrors([
                    'otp_code' =>
                        'Invalid OTP code.',
                ]);
        }

        if (
            Carbon::now()->greaterThan(
                $otp->expires_at
            )
        ) {
            return back()
                ->withErrors([
                    'otp_code' =>
                        'OTP code has expired.',
                ]);
        }

        $user = User::where(
            'email',
            $otp->email
        )->first();

        if (!$user) {
            return back()
                ->withErrors([
                    'email' =>
                        'User not found.',
                ]);
        }

        $user->update([
            'password' => Hash::make(
                $request->password
            ),
        ]);

        // OTP silinir
        $otp->delete();

        // Session təmizlənir
        session()->forget(
            'reset_email'
        );

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

        // User's own orders
        $orderCount = $user->orders()->count();

        // User's wishlist items
        $wishlistCount = $user->wishlists()->count();

        // User's reviews
        $reviewsCount = $user->reviews()->count();

        /*
        |--------------------------------------------------------------------------
        | Books Sold
        |--------------------------------------------------------------------------
        |
        | Only seller accounts can have sales.
        |
        | Count the actual quantity sold from orders belonging to
        | books owned by this seller.
        |
        | pending    -> not counted
        | processing -> counted
        | shipped    -> counted
        | delivered  -> counted
        | cancelled  -> not counted
        |
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
            $user->first_name . ' ' . $user->last_name
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

        $profileAddress = !empty($addressParts)
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

        $lastAccountUpdate = $user->updated_at
            ? $user->updated_at->format('F j, Y')
            : 'N/A';

        $lastLogin = $user->last_login_at
            ? $user->last_login_at->format(
                'F j, Y \a\t g:i A'
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
                /*
                |--------------------------------------------------------------------------
                | First Name
                |--------------------------------------------------------------------------
                */

                'first_name' => [
                    'required',
                    'string',
                    'min:2',
                    'max:50',
                    'regex:/^[\pL\pM]+(?:[\'-][\pL\pM]+)*$/u',
                ],

                /*
                |--------------------------------------------------------------------------
                | Last Name
                |--------------------------------------------------------------------------
                */

                'last_name' => [
                    'required',
                    'string',
                    'min:2',
                    'max:50',
                    'regex:/^[\pL\pM]+(?:[\'-][\pL\pM]+)*$/u',
                ],

                /*
                |--------------------------------------------------------------------------
                | Username
                |--------------------------------------------------------------------------
                */

                'username' => [
                    'nullable',
                    'string',
                    'min:3',
                    'max:100',
                    'regex:/^[A-Za-z0-9_.-]+$/',
                    Rule::unique(
                        'users',
                        'username'
                    )->ignore($user->id),
                ],

                /*
                |--------------------------------------------------------------------------
                | Email
                |--------------------------------------------------------------------------
                */

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

                /*
                |--------------------------------------------------------------------------
                | Phone Country Code
                |--------------------------------------------------------------------------
                */

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

                /*
                |--------------------------------------------------------------------------
                | Phone Number
                |--------------------------------------------------------------------------
                */

                'phone' => [
                    'nullable',
                    'string',
                    'regex:/^[0-9]+$/',
                ],

                /*
                |--------------------------------------------------------------------------
                | Date Of Birth
                |--------------------------------------------------------------------------
                */

                'date_of_birth' => [
                    'nullable',
                    'date',
                    'before_or_equal:today',
                ],

                /*
                |--------------------------------------------------------------------------
                | Gender
                |--------------------------------------------------------------------------
                */

                'gender' => [
                    'nullable',
                    Rule::in([
                        'male',
                        'female',
                        'prefer_not_to_say',
                    ]),
                ],

                /*
                |--------------------------------------------------------------------------
                | Country
                |--------------------------------------------------------------------------
                */

                'country' => [
                    'nullable',
                    'string',
                    'max:120',
                ],

                /*
                |--------------------------------------------------------------------------
                | City
                |--------------------------------------------------------------------------
                */

                'city' => [
                    'nullable',
                    'string',
                    'max:120',
                ],

                /*
                |--------------------------------------------------------------------------
                | State
                |--------------------------------------------------------------------------
                */

                'state' => [
                    'nullable',
                    'string',
                    'max:120',
                ],

                /*
                |--------------------------------------------------------------------------
                | Postal Code
                |--------------------------------------------------------------------------
                */

                'postal_code' => [
                    'nullable',
                    'string',
                    'max:30',
                ],

                /*
                |--------------------------------------------------------------------------
                | Address
                |--------------------------------------------------------------------------
                */

                'address' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                /*
                |--------------------------------------------------------------------------
                | Bio
                |--------------------------------------------------------------------------
                */

                'bio' => [
                    'nullable',
                    'string',
                    'max:300',
                ],

                /*
                |--------------------------------------------------------------------------
                | Profile Photo
                |--------------------------------------------------------------------------
                */

                'profile_photo' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:2048',
                ],
            ],
            [
                /*
                |--------------------------------------------------------------------------
                | Name Messages
                |--------------------------------------------------------------------------
                */

                'first_name.required' =>
                    'First name is required.',

                'first_name.min' =>
                    'First name must be at least 2 characters.',

                'first_name.max' =>
                    'First name may not exceed 50 characters.',

                'first_name.regex' =>
                    'First name may contain letters, hyphens, or apostrophes only.',

                'last_name.required' =>
                    'Last name is required.',

                'last_name.min' =>
                    'Last name must be at least 2 characters.',

                'last_name.max' =>
                    'Last name may not exceed 50 characters.',

                'last_name.regex' =>
                    'Last name may contain letters, hyphens, or apostrophes only.',

                /*
                |--------------------------------------------------------------------------
                | Username Messages
                |--------------------------------------------------------------------------
                */

                'username.min' =>
                    'Username must be at least 3 characters.',

                'username.max' =>
                    'Username may not exceed 100 characters.',

                'username.regex' =>
                    'Username may contain letters, numbers, dots, underscores, and hyphens only.',

                'username.unique' =>
                    'This username is already taken.',

                /*
                |--------------------------------------------------------------------------
                | Email Messages
                |--------------------------------------------------------------------------
                */

                'email.required' =>
                    'Email address is required.',

                'email.email' =>
                    'Please enter a valid email address.',

                'email.max' =>
                    'Email address may not exceed 255 characters.',

                'email.unique' =>
                    'This email is already registered.',

                /*
                |--------------------------------------------------------------------------
                | Phone Messages
                |--------------------------------------------------------------------------
                */

                'phone_country_code.required' =>
                    'Please select a country code.',

                'phone_country_code.in' =>
                    'Please select a valid country code.',

                'phone.regex' =>
                    'Phone number may contain numbers only.',

                /*
                |--------------------------------------------------------------------------
                | Date Of Birth Messages
                |--------------------------------------------------------------------------
                */

                'date_of_birth.date' =>
                    'Please enter a valid date of birth.',

                'date_of_birth.before_or_equal' =>
                    'Date of birth cannot be in the future.',

                /*
                |--------------------------------------------------------------------------
                | Photo Messages
                |--------------------------------------------------------------------------
                */

                'profile_photo.image' =>
                    'The profile photo must be a valid image.',

                'profile_photo.mimes' =>
                    'Profile photo must be JPG, JPEG, PNG, or WEBP.',

                'profile_photo.max' =>
                    'Profile photo may not exceed 2 MB.',
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

            $profilePhotoPath = $request
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
        |
        | Username Blade-dən gəlməsə belə,
        | mövcud username qorunur.
        |
        */

        $username = $validated['username']
            ?? $user->username;

        /*
        |--------------------------------------------------------------------------
        | Phone Number
        |--------------------------------------------------------------------------
        |
        | Seçilmiş ölkə koduna görə nömrənin
        | uzunluğu yoxlanılır.
        |
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
                return back()
                    ->withErrors([
                        'phone' =>
                            "Please enter a valid phone number for {$countryCode}.",
                    ])
                    ->withInput();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Full Phone Number
        |--------------------------------------------------------------------------
        */

        $fullPhone = $phoneNumber !== ''
            ? $validated['phone_country_code'] . $phoneNumber
            : null;

        /*
        |--------------------------------------------------------------------------
        | Update User
        |--------------------------------------------------------------------------
        |
        | Notification və privacy preference-ləri
        | artıq burada saxlanılmır.
        |
        | Onlar user_settings cədvəlindən idarə olunur.
        |
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
                    trim($validated['email'])
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
        | Success
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
    */

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

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
                    'min:' . Setting::get(
                        'minimum_password_length',
                        8
                    ),
                    'confirmed',
                ],
            ]
        );

        $user->update([
            'password' => Hash::make(
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

    /*
    |--------------------------------------------------------------------------
    | Destroy Profile
    |--------------------------------------------------------------------------
    */

    public function destroyProfile(Request $request)
    {
        $user = Auth::user();

        if ($user->profile_photo) {
            Storage::disk('public')->delete(
                $user->profile_photo
            );
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()
            ->route('frontend.home')
            ->with(
                'success',
                'Your account has been deleted.'
            );
    }
}