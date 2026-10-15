<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Exceptions\CheckoutRejected;
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
    public function index(Request $request)
    {
        if (!Setting::get('marketplace_enabled', true)) {
            abort(503);
        }

        $cart = session()->get('cart', []);
        $books = Book::whereKey(array_keys($cart))
            ->get()
            ->keyBy('id');

        foreach ($cart as $bookId => &$item) {
            $book = $books->get($bookId);

            if (!$book || $book->status !== 'approved') {
                unset($cart[$bookId]);
                continue;
            }

            $item['id'] = $book->id;
            $item['title'] = $book->title;
            $item['cover'] = $book->cover;
            $item['stock'] = $book->stock;
            $item['original_price'] = (float) $book->price;
            $item['price'] = $this->getActiveBookPrice($book);
            $item['discount_label'] = $book->discount_label;
        }

        unset($item);
        session()->put('cart', $cart);

        if (empty($cart)) {
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Your cart is empty.',
                ], 422);
            }

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

        $selectedShippingId = old(
            'shipping_id',
            $request->query('shipping_id')
        );

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

        if (
            $request->expectsJson() &&
            $request->query->has('shipping_id') &&
            !$selectedShipping
        ) {
            return response()->json([
                'message' => 'The selected shipping method is no longer available.',
            ], 422);
        }

        $selectedShippingId = $selectedShipping?->id;

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
            $shippingFee = $this->calculateShippingFee(
                $selectedShipping,
                $subtotal,
                $freeShippingThreshold
            );
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

        $paymentsEnabled = (bool) Setting::get(
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

        $paymentMethods = array_values(array_intersect(
            $paymentMethods,
            ['cash_on_delivery']
        ));
        $paymentsEnabled = $paymentsEnabled && $paymentMethods !== [];
        $defaultPaymentMethod = $paymentMethods[0] ?? null;

        if (request()->expectsJson()) {
            return response()->json([
                'cart' => array_values($cart),
                'subtotal' => $subtotal,
                'total_items' => $totalItems,
                'shipping_enabled' => $shippingEnabled,
                'shipping_methods' => $shippingMethods,
                'default_shipping_fee' => $defaultShippingFee,
                'selected_shipping_id' => $selectedShippingId,
                'shipping_fee' => $shippingFee,
                'free_shipping_threshold' => $freeShippingThreshold,
                'default_country' => $defaultCountry,
                'estimated_delivery_message' => $estimatedDeliveryMessage,
                'delivery_estimate' => $checkoutDeliveryEstimate,
                'grand_total' => $grandTotal,
                'payments_enabled' => $paymentsEnabled,
                'payment_methods' => $paymentMethods,
                'default_payment_method' => $defaultPaymentMethod,
            ]);
        }

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
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your cart is empty.',
                ], 422);
            }

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

        $paymentsEnabled = (bool) Setting::get(
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

        $allowedPaymentMethods = array_values(array_intersect(
            $allowedPaymentMethods,
            ['cash_on_delivery']
        ));
        $paymentsEnabled = $paymentsEnabled && $allowedPaymentMethods !== [];

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
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'No supported payment method is currently available.',
                ], 422);
            }

            return back()
                ->withInput()
                ->with(
                    'error',
                    'No supported payment method is currently available.'
                );
        }

        if (
            !in_array(
                $validated['payment_method'],
                $allowedPaymentMethods,
                true
            )
        ) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'The selected payment method is currently unavailable.',
                ], 422);
            }

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
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Please select a shipping method.',
                    ], 422);
                }

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
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'The selected shipping method is no longer available.',
                    ], 422);
                }

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

        /*
        |--------------------------------------------------------------------------
        | Delivery Estimate
        |--------------------------------------------------------------------------
        */

        $createdOrders = [];

        try {

            DB::transaction(function () use (
                $cart,
                $validated,
                &$createdOrders,
                $shippingEnabled,
                $selectedShipping,
                $freeShippingThreshold,
                $estimatedDeliveryMessage
            ) {
                $cartSubtotal = 0;

                foreach ($cart as $bookId => $item) {
                    $book = Book::whereKey($bookId)
                        ->lockForUpdate()
                        ->first();

                    if (!$book) {
                        throw new CheckoutRejected(
                            'One of the books in your cart no longer exists.'
                        );
                    }

                    $cartSubtotal += $this->getActiveBookPrice($book)
                        * (int) $item['quantity'];
                }

                $checkoutShipping = null;

                if ($shippingEnabled) {
                    $checkoutShipping = Shipping::where('status', true)
                        ->whereKey($selectedShipping->id)
                        ->lockForUpdate()
                        ->first();

                    if (!$checkoutShipping) {
                        throw new CheckoutRejected(
                            'The selected shipping method is no longer available.'
                        );
                    }
                }

                $cartShippingFee = $checkoutShipping
                    ? $this->calculateShippingFee(
                        $checkoutShipping,
                        $cartSubtotal,
                        $freeShippingThreshold
                    )
                    : 0;

                $deliveryEstimate =
                    $checkoutShipping?->delivery_time
                    ?: $estimatedDeliveryMessage;

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
                        throw new CheckoutRejected(
                            'One of the books in your cart no longer exists.'
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Book Availability
                    |--------------------------------------------------------------------------
                    */

                    if ($book->status !== 'approved') {
                        throw new CheckoutRejected(
                            "\"{$book->title}\" is no longer available."
                        );
                    }

                    if (!$book->seller) {
                        throw new CheckoutRejected(
                            "\"{$book->title}\" does not have an active seller."
                        );
                    }

                    $store = $book->seller->store;

                    if (!$store) {
                        throw new CheckoutRejected(
                            "\"{$book->title}\" seller store could not be found."
                        );
                    }

                    if (!$store->accept_orders) {
                        throw new CheckoutRejected(
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
                        throw new CheckoutRejected(
                            "Invalid quantity for \"{$book->title}\"."
                        );
                    }

                    if ($book->stock < $quantity) {
                        throw new CheckoutRejected(
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
                        throw new CheckoutRejected(
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
                            $checkoutShipping?->id,

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
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Your order has been placed successfully.',
                        'orders' => collect($createdOrders)->map(fn (Order $order) => [
                            'id' => $order->id,
                            'order_number' => $order->order_number,
                            'total_price' => $order->total_price,
                            'order_status' => $order->order_status,
                        ])->values(),
                    ], 201);
                }

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
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Order could not be created.',
                    ], 500);
                }

                return redirect()
                    ->route(
                        'frontend.orders'
                    )
                    ->with(
                        'error',
                        'Order could not be created.'
                    );
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Your order has been created.',
                    'orders' => collect($createdOrders)->map(fn (Order $order) => [
                        'id' => $order->id,
                        'order_number' => $order->order_number,
                        'total_price' => $order->total_price,
                        'order_status' => $order->order_status,
                    ])->values(),
                    'payment_url' => route('frontend.payment', $firstOrder->id),
                ], 201);
            }

            return redirect()
                ->route(
                    'frontend.payment',
                    $firstOrder->id
                );

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                if ($e instanceof CheckoutRejected) {
                    return response()->json([
                        'message' => $e->getMessage(),
                    ], 422);
                }

                report($e);

                return response()->json([
                    'message' => 'An error occurred while placing your order.',
                ], 500);
            }

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

    private function calculateShippingFee(
        Shipping $shipping,
        float $subtotal,
        float $freeShippingThreshold
    ): float {
        if (
            $freeShippingThreshold > 0 &&
            $subtotal >= $freeShippingThreshold
        ) {
            return 0;
        }

        return (float) $shipping->price;
    }

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
