<?php

namespace App\Http\Controllers\Auth;

use Throwable;

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
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
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
            'Frontend.my-profile',
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
            'Frontend.edit-profile',
            compact('user')
        );
    }

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

        $pendingEmail = strtolower(trim((string) $request->email));
        $emailChangeRequested = strtolower((string) $user->email) !== $pendingEmail;

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

        if ($emailChangeRequested) {
            $request->validateWithBag('profileUpdate', [
                'current_password' => ['required', 'current_password'],
            ]);

            if (RateLimiter::tooManyAttempts('account-email-change:' . $user->id, 3)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many email change requests. Please try again later.',
                ], 429);
            }
        }

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

        $pendingEmail = strtolower(trim((string) $validated['email']));

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
                $emailChangeRequested ? $user->email : $pendingEmail,

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

        if ($emailChangeRequested) {
            $otpCode = (string) random_int(100000, 999999);
            Otp::updateOrCreate(
                ['email' => $pendingEmail, 'purpose' => 'email_change'],
                ['otp_code' => $otpCode, 'expires_at' => Carbon::now()->addMinutes(10)]
            );
            session([
                'sensitive_action' => 'email_change',
                'sensitive_action_email' => strtolower((string) $user->email),
                'pending_email' => $pendingEmail,
                'email_verification_email' => $pendingEmail,
            ]);

            try {
                Mail::raw(
                    "Your SecondBook email change verification code is: {$otpCode}\n\nThis code will expire in 10 minutes.",
                    fn ($message) => $message->to($pendingEmail)->subject('SecondBook Email Change Verification')
                );
            } catch (Throwable $e) {
                report($e);
                Otp::where('email', $pendingEmail)->where('purpose', 'email_change')->delete();
                session()->forget(['sensitive_action', 'sensitive_action_email', 'pending_email', 'email_verification_email']);

                return response()->json([
                    'success' => false,
                    'message' => 'Your profile was saved, but the email verification message could not be sent. Please retry the email change.',
                ], 500);
            }

            RateLimiter::hit('account-email-change:' . $user->id, 600);
        }

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

                'message' => $emailChangeRequested
                    ? 'Your profile was saved. Verify the code sent to your new email address to complete the email change.'
                    : 'Profile updated successfully.',

                'redirect' => $emailChangeRequested
                    ? route('frontend.auth.email.verify')
                    : null,

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
            ->to($emailChangeRequested ? route('frontend.auth.email.verify') : route('profile.edit'))
            ->with(
                'success',
                'Profile updated successfully.'
            );
    }

    public function removeProfilePhoto(Request $request)
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

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile photo removed successfully.',
                'profile_photo_url' => null,
            ]);
        }

        return redirect()
            ->route('profile.edit')
            ->with(
                'success',
                'Profile photo removed successfully.'
            );
    }

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
}
