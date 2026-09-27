<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\Shipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!Setting::get('marketplace_enabled', true)) {
            abort(503);
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('frontend.cart')
                ->with('error', 'Your cart is empty.');
        }

        /*
        |--------------------------------------------------------------------------
        | Cart Subtotal
        |--------------------------------------------------------------------------
        */

        $subtotal = collect($cart)->sum(function ($item) {
            return (float) $item['price'] * (int) $item['quantity'];
        });

        $totalItems = collect($cart)->sum('quantity');

        /*
        |--------------------------------------------------------------------------
        | Shipping Settings
        |--------------------------------------------------------------------------
        */

        $shippingEnabled = Setting::get(
            'shipping_enabled',
            true
        );

        $freeShippingThreshold = (float) Setting::get(
            'free_shipping_threshold',
            0
        );

        $defaultShippingFee = (float) Setting::get(
            'default_shipping_fee',
            0
        );

        $defaultCountry = Setting::get(
            'default_country'
        );

        $estimatedDeliveryMessage = Setting::get(
            'estimated_delivery_message',
            ''
        );

        /*
        |--------------------------------------------------------------------------
        | Shipping Methods
        |--------------------------------------------------------------------------
        */

        $shippingMethods = collect();

        if ($shippingEnabled) {
            $shippingMethods = Shipping::where(
                'status',
                true
            )
                ->orderBy('price')
                ->orderBy('name')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Default Shipping Method
        |--------------------------------------------------------------------------
        */

        $selectedShippingId = old('shipping_id');

        if (
            !$selectedShippingId &&
            $shippingMethods->isNotEmpty()
        ) {
            $selectedShippingId =
                $shippingMethods->first()->id;
        }

        $selectedShipping = null;

        if ($selectedShippingId) {
            $selectedShipping =
                $shippingMethods->firstWhere(
                    'id',
                    (int) $selectedShippingId
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Shipping Fee
        |--------------------------------------------------------------------------
        */

        $shippingFee = 0;

        if (
            $shippingEnabled &&
            $selectedShipping
        ) {
            if (
                $freeShippingThreshold > 0 &&
                $subtotal >= $freeShippingThreshold
            ) {
                $shippingFee = 0;
            } else {
                $shippingFee = $defaultShippingFee;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Delivery Estimate
        |--------------------------------------------------------------------------
        */

        $checkoutDeliveryEstimate =
            $selectedShipping?->delivery_time
            ?: $estimatedDeliveryMessage;

        $grandTotal =
            $subtotal + $shippingFee;

        /*
        |--------------------------------------------------------------------------
        | Payment Settings
        |--------------------------------------------------------------------------
        */

        $paymentsEnabled = Setting::get(
            'payments_enabled',
            true
        );

        $paymentMethods = Setting::get(
            'payment_methods',
            [
                'cash_on_delivery',
                'credit_card',
                'debit_card',
                'paypal',
            ]
        );

        if (!is_array($paymentMethods)) {
            $paymentMethods = [
                'cash_on_delivery',
                'credit_card',
                'debit_card',
                'paypal',
            ];
        }

        $defaultPaymentMethod = Setting::get(
            'default_payment_method',
            'cash_on_delivery'
        );

        return view(
            'Frontend.checkout',
            compact(
                'cart',
                'subtotal',
                'totalItems',
                'shippingEnabled',
                'shippingMethods',
                'defaultShippingFee',
                'selectedShippingId',
                'shippingFee',
                'freeShippingThreshold',
                'defaultCountry',
                'estimatedDeliveryMessage',
                'checkoutDeliveryEstimate',
                'grandTotal',
                'paymentsEnabled',
                'paymentMethods',
                'defaultPaymentMethod'
            )
        );
    }

    public function store(Request $request)
    {
        if (!Setting::get('marketplace_enabled', true)) {
            abort(503);
        }

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('frontend.cart')
                ->with(
                    'error',
                    'Your cart is empty.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Settings
        |--------------------------------------------------------------------------
        */

        $paymentsEnabled = Setting::get(
            'payments_enabled',
            true
        );

        $allowedPaymentMethods = Setting::get(
            'payment_methods',
            [
                'cash_on_delivery',
                'credit_card',
                'debit_card',
                'paypal',
            ]
        );

        if (!is_array($allowedPaymentMethods)) {
            $allowedPaymentMethods = [
                'cash_on_delivery',
                'credit_card',
                'debit_card',
                'paypal',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'full_name' => [
                'required',
                'string',
                'max:255',
            ],

            'phone' => [
                'required',
                'string',
                'max:50',
            ],

            'country' => [
                'required',
                'string',
                'max:100',
            ],

            'city' => [
                'required',
                'string',
                'max:100',
            ],

            'postal_code' => [
                'nullable',
                'string',
                'max:20',
            ],

            'address' => [
                'required',
                'string',
                'max:1000',
            ],

            'note' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'payment_method' => [
                'required',
                'string',
                'in:cash_on_delivery,credit_card,debit_card,paypal',
            ],

            'shipping_id' => [
                'nullable',
                'integer',
                'exists:shippings,id',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Payment Check
        |--------------------------------------------------------------------------
        */

        if (!$paymentsEnabled) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Payments are currently disabled.'
                );
        }

        if (
            !in_array(
                $validated['payment_method'],
                $allowedPaymentMethods,
                true
            )
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'The selected payment method is currently unavailable.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Shipping Settings
        |--------------------------------------------------------------------------
        */

        $shippingEnabled = Setting::get(
            'shipping_enabled',
            true
        );

        $freeShippingThreshold = (float) Setting::get(
            'free_shipping_threshold',
            0
        );

        $defaultShippingFee = (float) Setting::get(
            'default_shipping_fee',
            0
        );

        $estimatedDeliveryMessage = Setting::get(
            'estimated_delivery_message',
            ''
        );

        /*
        |--------------------------------------------------------------------------
        | Shipping Method
        |--------------------------------------------------------------------------
        */

        $selectedShipping = null;

        if ($shippingEnabled) {

            if (empty($validated['shipping_id'])) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Please select a shipping method.'
                    );
            }

            $selectedShipping = Shipping::where(
                'status',
                true
            )->find(
                $validated['shipping_id']
            );

            if (!$selectedShipping) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'The selected shipping method is no longer available.'
                    );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Cart Subtotal
        |--------------------------------------------------------------------------
        */

        $cartSubtotal = collect($cart)->sum(function ($item) {
            return (float) $item['price'] *
                (int) $item['quantity'];
        });

        /*
        |--------------------------------------------------------------------------
        | Shipping Fee
        |--------------------------------------------------------------------------
        |
        | Below free shipping threshold:
        |     default_shipping_fee
        |
        | At / above threshold:
        |     FREE
        |
        */

        $cartShippingFee = 0;

        if (
            $shippingEnabled &&
            $selectedShipping
        ) {

            if (
                $freeShippingThreshold > 0 &&
                $cartSubtotal >= $freeShippingThreshold
            ) {
                $cartShippingFee = 0;
            } else {
                $cartShippingFee =
                    $defaultShippingFee;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Delivery Estimate
        |--------------------------------------------------------------------------
        */

        $deliveryEstimate =
            $selectedShipping?->delivery_time
            ?: $estimatedDeliveryMessage;

        $createdOrders = [];

        try {

            DB::transaction(function () use (
                $cart,
                $validated,
                &$createdOrders,
                $shippingEnabled,
                $selectedShipping,
                $cartShippingFee,
                $deliveryEstimate
            ) {

                $orderIndex = 0;

                foreach ($cart as $bookId => $item) {

                    /*
                    |--------------------------------------------------------------------------
                    | Lock Book
                    |--------------------------------------------------------------------------
                    */

                    $book = Book::with(
                        'seller.store'
                    )
                        ->whereKey($bookId)
                        ->lockForUpdate()
                        ->first();

                    if (!$book) {
                        throw new \Exception(
                            'One of the books in your cart no longer exists.'
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Book Availability
                    |--------------------------------------------------------------------------
                    */

                    if ($book->status !== 'approved') {
                        throw new \Exception(
                            "\"{$book->title}\" is no longer available."
                        );
                    }

                    if (!$book->seller) {
                        throw new \Exception(
                            "\"{$book->title}\" does not have an active seller."
                        );
                    }

                    $store = $book->seller->store;

                    if (!$store) {
                        throw new \Exception(
                            "\"{$book->title}\" seller store could not be found."
                        );
                    }

                    if (!$store->accept_orders) {
                        throw new \Exception(
                            "This store is currently not accepting orders for \"{$book->title}\"."
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Quantity
                    |--------------------------------------------------------------------------
                    */

                    $quantity = (int) $item['quantity'];

                    if ($quantity <= 0) {
                        throw new \Exception(
                            "Invalid quantity for \"{$book->title}\"."
                        );
                    }

                    if ($book->stock < $quantity) {
                        throw new \Exception(
                            "There is not enough stock for \"{$book->title}\"."
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Current Book Price
                    |--------------------------------------------------------------------------
                    |
                    | IMPORTANT:
                    | Always calculate the price from the fresh database
                    | record. Never trust the session cart price.
                    |
                    */

                    $bookPrice =
                        $this->getActiveBookPrice($book);

                    $bookTotal =
                        $bookPrice * $quantity;

                    /*
                    |--------------------------------------------------------------------------
                    | Minimum Order
                    |--------------------------------------------------------------------------
                    */

                    $minimumOrderAmount =
                        (float) Setting::get(
                            'minimum_order_amount',
                            0
                        );

                    $storeMinimumOrderAmount =
                        (float) $store->minimum_order_amount;

                    $effectiveMinimumOrderAmount =
                        $storeMinimumOrderAmount > 0
                            ? $storeMinimumOrderAmount
                            : $minimumOrderAmount;

                    if (
                        $effectiveMinimumOrderAmount > 0 &&
                        $bookTotal <
                        $effectiveMinimumOrderAmount
                    ) {
                        throw new \Exception(
                            'The minimum order amount is ₼' .
                            number_format(
                                $effectiveMinimumOrderAmount,
                                2
                            ) .
                            '.'
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Shipping Only On First Order
                    |--------------------------------------------------------------------------
                    */

                    $shippingFee = 0;

                    if (
                        $shippingEnabled &&
                        $cartShippingFee > 0 &&
                        $orderIndex === 0
                    ) {
                        $shippingFee =
                            $cartShippingFee;
                    }

                    $grandTotal =
                        $bookTotal + $shippingFee;

                    /*
                    |--------------------------------------------------------------------------
                    | Order Status
                    |--------------------------------------------------------------------------
                    */

                    $configuredOrderStatus =
                        Setting::get(
                            'default_order_status',
                            null
                        );

                    $orderStatus =
                        $store->auto_approve_orders
                            ? 'processing'
                            : (
                                $configuredOrderStatus
                                ?: 'pending'
                            );

                    $processingDeadline =
                        now()->addDays(
                            $store->processing_time
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | Order Number
                    |--------------------------------------------------------------------------
                    */

                    $orderNumberFormat =
                        Setting::get(
                            'order_number_format',
                            '#SB-{YYYY}-{####}'
                        );

                    $orderNumber =
                        str_replace(
                            [
                                '{YYYY}',
                                '{YmdHis}',
                                '{Y}',
                                '{m}',
                                '{d}',
                                '{H}',
                                '{i}',
                                '{s}',
                                '{random}',
                            ],
                            [
                                now()->format('Y'),
                                now()->format('YmdHis'),
                                now()->format('Y'),
                                now()->format('m'),
                                now()->format('d'),
                                now()->format('H'),
                                now()->format('i'),
                                now()->format('s'),
                                strtoupper(
                                    Str::random(6)
                                ),
                            ],
                            $orderNumberFormat
                        );

                    $orderNumber =
                        preg_replace_callback(
                            '/#{2,}/',
                            function ($matches) {

                                $length =
                                    strlen(
                                        $matches[0]
                                    );

                                return str_pad(
                                    (string) random_int(
                                        0,
                                        (10 ** $length) - 1
                                    ),
                                    $length,
                                    '0',
                                    STR_PAD_LEFT
                                );
                            },
                            $orderNumber
                        );

                    if (
                        empty(
                            trim($orderNumber)
                        ) ||
                        $orderNumber ===
                        $orderNumberFormat
                    ) {
                        $orderNumber =
                            '#SB-' .
                            now()->format('Y') .
                            '-' .
                            str_pad(
                                (string) random_int(
                                    0,
                                    9999
                                ),
                                4,
                                '0',
                                STR_PAD_LEFT
                            );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Create Order
                    |--------------------------------------------------------------------------
                    */

                    $order = Order::create([
                        'order_number' =>
                            $orderNumber,

                        'user_id' =>
                            auth()->id(),

                        'book_id' =>
                            $book->id,

                        'book_price' =>
                            $bookPrice,

                        'quantity' =>
                            $quantity,

                        'total_price' =>
                            $grandTotal,

                        'shipping_id' =>
                            $selectedShipping?->id,

                        'shipping_fee' =>
                            $shippingFee,

                        'payment_method' =>
                            $validated[
                                'payment_method'
                            ],

                        'payment_status' =>
                            'pending',

                        'order_status' =>
                            $orderStatus,

                        'processing_deadline' =>
                            $processingDeadline,

                        'order_note' =>
                            $store->order_note,

                        'full_name' =>
                            $validated['full_name'],

                        'phone' =>
                            $validated['phone'],

                        'country' =>
                            $validated['country'],

                        'city' =>
                            $validated['city'],

                        'postal_code' =>
                            $validated[
                                'postal_code'
                            ] ?? null,

                        'address' =>
                            $validated['address'],

                        'delivery_estimate' =>
                            $deliveryEstimate,

                        'note' =>
                            $validated['note'] ?? null,
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Payment
                    |--------------------------------------------------------------------------
                    */

                    Payment::create([
                        'transaction_id' =>
                            'TXN-' .
                            strtoupper(
                                Str::random(12)
                            ),

                        'order_id' =>
                            $order->id,

                        'amount' =>
                            $grandTotal,

                        'payment_method' =>
                            $validated[
                                'payment_method'
                            ],

                        'payment_status' =>
                            'pending',

                        'paid_at' =>
                            null,

                        'note' =>
                            null,
                    ]);

                    $createdOrders[] =
                        $order;

                    /*
                    |--------------------------------------------------------------------------
                    | Reduce Stock
                    |--------------------------------------------------------------------------
                    */

                    $book->decrement(
                        'stock',
                        $quantity
                    );

                    $orderIndex++;
                }
            });

            /*
            |--------------------------------------------------------------------------
            | Clear Cart
            |--------------------------------------------------------------------------
            */

            session()->forget('cart');

            /*
            |--------------------------------------------------------------------------
            | Cash On Delivery
            |--------------------------------------------------------------------------
            */

            if (
                $validated['payment_method'] ===
                'cash_on_delivery'
            ) {
                return redirect()
                    ->route(
                        'frontend.orders'
                    )
                    ->with(
                        'success',
                        'Your order has been placed successfully.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Online Payment
            |--------------------------------------------------------------------------
            */

            $firstOrder =
                $createdOrders[0] ?? null;

            if (!$firstOrder) {
                return redirect()
                    ->route(
                        'frontend.orders'
                    )
                    ->with(
                        'error',
                        'Order could not be created.'
                    );
            }

            return redirect()
                ->route(
                    'frontend.payment',
                    $firstOrder->id
                );

        } catch (\Exception $e) {

            return redirect()
                ->route(
                    'frontend.cart'
                )
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Active Book Price
    |--------------------------------------------------------------------------
    |
    | This method makes sure future/expired discounts are not applied.
    |
    */

    private function getActiveBookPrice(Book $book): float
    {
        $originalPrice =
            (float) $book->price;

        if (
            ($book->discount_type ?? 'none') ===
            'none'
        ) {
            return $originalPrice;
        }

        if (
            (float) (
                $book->discount_value ?? 0
            ) <= 0
        ) {
            return $originalPrice;
        }

        /*
        |--------------------------------------------------------------------------
        | Discount Start
        |--------------------------------------------------------------------------
        */

        if (
            $book->discount_start_at &&
            $book->discount_start_at->isFuture()
        ) {
            return $originalPrice;
        }

        /*
        |--------------------------------------------------------------------------
        | Discount End
        |--------------------------------------------------------------------------
        */

        if (
            $book->discount_end_at &&
            $book->discount_end_at->isPast()
        ) {
            return $originalPrice;
        }

        $discountValue =
            (float) $book->discount_value;

        /*
        |--------------------------------------------------------------------------
        | Percentage Discount
        |--------------------------------------------------------------------------
        */

        if (
            $book->discount_type ===
            'percentage'
        ) {

            $discountValue =
                min(
                    max(
                        $discountValue,
                        0
                    ),
                    100
                );

            $discountAmount =
                $originalPrice *
                ($discountValue / 100);

            return round(
                max(
                    0,
                    $originalPrice -
                    $discountAmount
                ),
                2
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Fixed Discount
        |--------------------------------------------------------------------------
        */

        if (
            $book->discount_type ===
            'fixed'
        ) {
            return round(
                max(
                    0,
                    $originalPrice -
                    $discountValue
                ),
                2
            );
        }

        return $originalPrice;
    }
}

