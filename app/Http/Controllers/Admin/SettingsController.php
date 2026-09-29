<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function __construct(
        protected ActivityLogService $activityLogService
    ) {
    }

    public function index()
    {
        $settings = Setting::query()->get()->keyBy('key');

        $paymentMethods = [
            'cash_on_delivery',
            'credit_card',
            'debit_card',
            'paypal',
        ];

        return view('Admin.settings.index', compact(
            'settings',
            'paymentMethods'
        ));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:120'],
            'site_description' => ['nullable', 'string', 'max:500'],
            'support_email' => ['nullable', 'email', 'max:255'],
            'support_phone' => ['nullable', 'string', 'max:40'],
            'address' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'currency' => ['required', 'string', 'max:10'],
            'timezone' => ['required', 'timezone'],
            'default_country' => ['nullable', 'string', 'max:100'],
            'minimum_order_amount' => ['nullable', 'numeric', 'min:0'],
            'default_shipping_fee' => ['nullable', 'numeric', 'min:0'],
            'free_shipping_threshold' => ['nullable', 'numeric', 'min:0'],
            'estimated_delivery_message' => ['nullable', 'string', 'max:255'],
            'default_order_status' => [
                'required',
                Rule::in([
                    'pending',
                    'processing',
                    'shipped',
                    'delivered',
                    'cancelled',
                ]),
            ],
            'order_number_format' => ['required', 'string', 'max:50'],
            'cancel_order_period' => [
                'required',
                'integer',
                'min:0',
                'max:720',
            ],
            'default_payment_method' => [
                'required',
                Rule::in([
                    'cash_on_delivery',
                    'credit_card',
                    'debit_card',
                    'paypal',
                ]),
            ],
            'payment_methods' => ['nullable', 'array'],
            'payment_methods.*' => [
                Rule::in([
                    'cash_on_delivery',
                    'credit_card',
                    'debit_card',
                    'paypal',
                ]),
            ],
            'meta_title' => ['nullable', 'string', 'max:160'],
            'meta_description' => ['nullable', 'string', 'max:320'],
            'keywords' => ['nullable', 'string', 'max:500'],
            'google_analytics_id' => ['nullable', 'string', 'max:100'],
            'google_search_console_verification' => [
                'nullable',
                'string',
                'max:255',
            ],
            'facebook' => ['nullable', 'url', 'max:255'],
            'instagram' => ['nullable', 'url', 'max:255'],
            'tiktok' => ['nullable', 'url', 'max:255'],
            'youtube' => ['nullable', 'url', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:40'],
            'login_attempt_limit' => [
                'required',
                'integer',
                'min:1',
                'max:20',
            ],
            'session_lifetime' => [
                'required',
                'integer',
                'min:1',
                'max:10080',
            ],
            'minimum_password_length' => [
                'required',
                'integer',
                'min:8',
                'max:128',
            ],
            'privacy_policy' => ['nullable', 'string', 'max:20000'],
            'terms_conditions' => ['nullable', 'string', 'max:20000'],
            'refund_policy' => ['nullable', 'string', 'max:20000'],
            'shipping_policy' => ['nullable', 'string', 'max:20000'],
            'cookie_notice' => ['nullable', 'string', 'max:5000'],
            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'favicon' => [
                'nullable',
                'image',
                'mimes:ico,png,jpg,jpeg,webp',
                'max:1024',
            ],
            'open_graph_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
        ]);

        $groups = [
            'general' => [
                'site_name',
                'site_description',
                'support_email',
                'support_phone',
                'address',
                'country',
                'city',
                'currency',
                'timezone',
                'maintenance_mode',
            ],

            'store' => [
                'marketplace_enabled',
                'user_registration_enabled',
                'seller_registration_enabled',
                'seller_approval_required',
                'book_approval_required',
                'reviews_enabled',
                'stock_management_enabled',
                'minimum_order_amount',
            ],

            'orders' => [
                'default_order_status',
                'order_number_format',
                'cancel_order_period',
                'auto_cancel_pending_orders',
                'customer_order_notifications',
            ],

            'payments' => [
                'payments_enabled',
                'default_payment_method',
            ],

            'shipping' => [
                'shipping_enabled',
                'default_shipping_fee',
                'free_shipping_threshold',
                'default_country',
                'estimated_delivery_message',
            ],

            'seo' => [
                'meta_title',
                'meta_description',
                'keywords',
                'search_engine_indexing',
                'google_analytics_id',
                'google_search_console_verification',
            ],

            'social' => [
                'facebook',
                'instagram',
                'tiktok',
                'youtube',
                'whatsapp',
            ],

            'security' => [
                'login_attempt_limit',
                'session_lifetime',
                'minimum_password_length',
                'two_factor_authentication_enabled',
                'admin_session_security',
            ],

            'legal' => [
                'privacy_policy',
                'terms_conditions',
                'refund_policy',
                'shipping_policy',
                'cookie_notice',
            ],
        ];

        $booleanKeys = [
            'maintenance_mode',
            'marketplace_enabled',
            'user_registration_enabled',
            'seller_registration_enabled',
            'seller_approval_required',
            'book_approval_required',
            'reviews_enabled',
            'stock_management_enabled',
            'auto_cancel_pending_orders',
            'customer_order_notifications',
            'payments_enabled',
            'shipping_enabled',
            'search_engine_indexing',
            'two_factor_authentication_enabled',
            'admin_session_security',
        ];

        $integerKeys = [
            'cancel_order_period',
            'login_attempt_limit',
            'session_lifetime',
            'minimum_password_length',
        ];

        foreach ($groups as $group => $keys) {
            foreach ($keys as $key) {
                $type = in_array($key, $booleanKeys, true)
                    ? 'boolean'
                    : (
                        in_array($key, $integerKeys, true)
                            ? 'integer'
                            : 'text'
                    );

                $value = $type === 'boolean'
                    ? $request->boolean($key)
                    : ($validated[$key] ?? '');

                Setting::put(
                    $key,
                    $value,
                    $group,
                    $type
                );
            }
        }

        $methods = $request->input('payment_methods', []);

        Setting::put(
            'payment_methods',
            array_values(
                array_intersect(
                    $methods,
                    [
                        'cash_on_delivery',
                        'credit_card',
                        'debit_card',
                        'paypal',
                    ]
                )
            ),
            'payments',
            'json'
        );

        foreach ([
            'logo' => 'general',
            'favicon' => 'general',
            'open_graph_image' => 'seo',
        ] as $field => $group) {
            if ($request->hasFile($field)) {
                $oldPath = Setting::get($field);

                if ($oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }

                Setting::put(
                    $field,
                    $request->file($field)->store('settings', 'public'),
                    $group
                );
            }
        }

        if ($request->filled('theme')) {
            $request->validate([
                'theme' => ['in:light,dark'],
            ]);

            session([
                'admin_theme' => $request->input('theme'),
            ]);
        }

        $this->activityLogService->log(
            'updated',
            'Settings',
            'System settings were updated.'
        );

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Settings updated successfully.',
            ]);
        }

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
            }
}

