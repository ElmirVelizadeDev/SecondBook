<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SellerApplication;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerApplicationController extends Controller
{
    private NotificationService $notificationService;

    public function __construct(
        NotificationService $notificationService
    ) {
        $this->notificationService = $notificationService;
    }

    /**
     * Show seller application page.
     */
    public function create()
    {
        $user = Auth::user();

        /*
         * User is not authenticated.
         */
        if (!$user) {
            return redirect()
                ->route('frontend.auth.login')
                ->with(
                    'info',
                    'Please sign in to become a seller.'
                );
        }

        /*
         * User email is not verified.
         */
        if (is_null($user->email_verified_at)) {
            return redirect()
                ->route('frontend.auth.email.verify')
                ->with(
                    'info',
                    'Please verify your email address before becoming a seller.'
                );
        }

        /*
         * User is already a seller.
         */
        if ($user->isSeller()) {
            return redirect()
                ->route('frontend.home')
                ->with(
                    'info',
                    'You are already a seller.'
                );
        }

        /*
         * Get the user's latest seller application.
         */
        $application = $user->sellerApplications()
            ->latest()
            ->first();

        return view(
            'Frontend.seller-application',
            compact('application')
        );
    }

    /**
     * Store seller application.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        /*
         * User is not authenticated.
         */
        if (!$user) {
            return redirect()
                ->route('frontend.auth.login')
                ->with(
                    'info',
                    'Please sign in to become a seller.'
                );
        }

        /*
         * User email is not verified.
         */
        if (is_null($user->email_verified_at)) {
            return redirect()
                ->route('frontend.auth.email.verify')
                ->with(
                    'info',
                    'Please verify your email address before becoming a seller.'
                );
        }

        /*
         * User is already a seller.
         */
        if ($user->isSeller()) {
            return redirect()
                ->route('frontend.home')
                ->with(
                    'info',
                    'You are already a seller.'
                );
        }

        /*
         * Prevent multiple pending applications.
         */
        $existingApplication = $user->sellerApplications()
            ->where('status', 'pending')
            ->exists();

        if ($existingApplication) {
            return back()
                ->with(
                    'info',
                    'You already have a pending seller application.'
                );
        }

        /*
         * Validate seller application.
         */
        $validated = $request->validate([
            /*
             * Store name
             * Required
             * Maximum 100 characters
             */
            'store_name' => [
                'required',
                'string',
                'max:100',
            ],

            /*
             * Store description
             * Optional
             * Maximum 1000 characters
             */
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            /*
             * Phone number
             * Optional
             * Maximum 15 characters
             * Only numbers and an optional + at the beginning
             */
            'phone' => [
                'nullable',
                'string',
                'max:15',
                'regex:/^\+?[0-9]+$/',
            ],

            /*
             * Address
             * Optional
             * Maximum 255 characters
             */
            'address' => [
                'nullable',
                'string',
                'max:255',
            ],
        ], [
            /*
             * Custom validation messages.
             */
            'store_name.required' =>
                'Please enter your store name.',

            'store_name.string' =>
                'Store name must be a valid text.',

            'store_name.max' =>
                'Store name may not be longer than 100 characters.',

            'description.string' =>
                'Store description must be a valid text.',

            'description.max' =>
                'Store description may not be longer than 1000 characters.',

            'phone.string' =>
                'Phone number must be a valid text.',

            'phone.max' =>
                'Phone number may not be longer than 15 characters.',

            'phone.regex' =>
                'Phone number may contain only numbers and an optional + at the beginning.',

            'address.string' =>
                'Address must be a valid text.',

            'address.max' =>
                'Address may not be longer than 255 characters.',
        ]);

        /*
         * Create seller application.
         */
        $application = SellerApplication::create([
            'user_id' => $user->id,
            'store_name' => $validated['store_name'],
            'description' => $validated['description'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'status' => 'pending',
        ]);

        /*
         * Notify all active admins.
         */
        $this->notificationService->sendToAdmins(
            'seller',
            'New Seller Application',
            "User \"{$user->name}\" has submitted a new seller application for \"{$application->store_name}\"."
        );

        /*
         * Redirect back to seller application page.
         */
        return redirect()
            ->route('frontend.seller-application')
            ->with(
                'success',
                'Your seller application has been submitted successfully and is waiting for admin approval.'
            );
    }
}

