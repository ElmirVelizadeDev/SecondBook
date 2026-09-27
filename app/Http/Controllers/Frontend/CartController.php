<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Setting;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     */
    public function index()
    {
        if (!Setting::get('marketplace_enabled', true)) {
            abort(503);
        }

        $cart = session()->get('cart', []);

        /*
        |--------------------------------------------------------------------------
        | REFRESH CART PRICES
        |--------------------------------------------------------------------------
        |
        | Recalculate the price of every cart item from the current book data.
        | This makes sure that an active discount is reflected in the cart.
        |
        */

        foreach ($cart as $bookId => &$item) {

            $book = Book::find($bookId);

            if (!$book || $book->status !== 'approved') {
                unset($cart[$bookId]);
                continue;
            }

            $item['id'] = $book->id;
            $item['title'] = $book->title;
            $item['cover'] = $book->cover;
            $item['stock'] = $book->stock;

            $item['original_price'] = (float) $book->price;

            $item['price'] = $this->getActivePrice($book);

            $item['discount_label'] = $this->getDiscountLabel($book);
        }

        unset($item);

        session()->put('cart', $cart);

        /*
        |--------------------------------------------------------------------------
        | SUBTOTAL
        |--------------------------------------------------------------------------
        */

        $subtotal = collect($cart)->sum(function ($item) {
            return (float) $item['price'] * (int) $item['quantity'];
        });

        /*
        |--------------------------------------------------------------------------
        | TOTAL ITEMS
        |--------------------------------------------------------------------------
        */

        $totalItems = collect($cart)->sum('quantity');

        return view('Frontend.cart', compact(
            'cart',
            'subtotal',
            'totalItems'
        ));
    }


    /**
     * Add a book to the cart.
     */
    public function add(Request $request, Book $book)
    {
        if (!Setting::get('marketplace_enabled', true)) {
            abort(503);
        }

        /*
        |--------------------------------------------------------------------------
        | BOOK STATUS
        |--------------------------------------------------------------------------
        */

        if ($book->status !== 'approved') {

            return response()->json([
                'success' => false,
                'message' => 'This book is not available for purchase.',
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | STOCK
        |--------------------------------------------------------------------------
        */

        if ($book->stock <= 0) {

            return response()->json([
                'success' => false,
                'message' => 'This book is currently out of stock.',
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | QUANTITY
        |--------------------------------------------------------------------------
        */

        $quantity = max(
            (int) $request->input('quantity', 1),
            1
        );


        /*
        |--------------------------------------------------------------------------
        | CART
        |--------------------------------------------------------------------------
        */

        $cart = session()->get('cart', []);


        /*
        |--------------------------------------------------------------------------
        | CURRENT ACTIVE PRICE
        |--------------------------------------------------------------------------
        */

        $originalPrice = (float) $book->price;

        $currentPrice = $this->getActivePrice($book);

        $discountLabel = $this->getDiscountLabel($book);


        /*
        |--------------------------------------------------------------------------
        | BOOK ALREADY EXISTS
        |--------------------------------------------------------------------------
        */

        if (isset($cart[$book->id])) {

            $newQuantity =
                (int) $cart[$book->id]['quantity'] + $quantity;


            if ($newQuantity > $book->stock) {

                return response()->json([
                    'success' => false,
                    'message' =>
                        'You cannot add more than the available stock.',
                ], 422);
            }


            /*
            |--------------------------------------------------------------------------
            | REFRESH EXISTING ITEM DATA
            |--------------------------------------------------------------------------
            */

            $cart[$book->id]['id'] =
                $book->id;

            $cart[$book->id]['title'] =
                $book->title;

            $cart[$book->id]['original_price'] =
                $originalPrice;

            $cart[$book->id]['price'] =
                $currentPrice;

            $cart[$book->id]['discount_label'] =
                $discountLabel;

            $cart[$book->id]['cover'] =
                $book->cover;

            $cart[$book->id]['quantity'] =
                $newQuantity;

            $cart[$book->id]['stock'] =
                $book->stock;
        }


        /*
        |--------------------------------------------------------------------------
        | NEW BOOK
        |--------------------------------------------------------------------------
        */

        else {

            if ($quantity > $book->stock) {

                return response()->json([
                    'success' => false,
                    'message' =>
                        'The requested quantity is not available.',
                ], 422);
            }


            $cart[$book->id] = [

                'id' =>
                    $book->id,

                'title' =>
                    $book->title,

                'original_price' =>
                    $originalPrice,

                'price' =>
                    $currentPrice,

                'discount_label' =>
                    $discountLabel,

                'cover' =>
                    $book->cover,

                'quantity' =>
                    $quantity,

                'stock' =>
                    $book->stock,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | SAVE CART
        |--------------------------------------------------------------------------
        */

        session()->put('cart', $cart);


        /*
        |--------------------------------------------------------------------------
        | TOTAL CART ITEMS
        |--------------------------------------------------------------------------
        */

        $cartCount =
            collect($cart)->sum('quantity');


        /*
        |--------------------------------------------------------------------------
        | AJAX RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' =>
                true,

            'message' =>
                'Book added to cart successfully!',

            'cart_count' =>
                $cartCount,

        ]);
    }


    /**
     * Update cart item quantity.
     */
    public function update(Request $request, $bookId)
    {
        if (!Setting::get('marketplace_enabled', true)) {
            abort(503);
        }


        /*
        |--------------------------------------------------------------------------
        | QUANTITY
        |--------------------------------------------------------------------------
        */

        $quantity =
            (int) $request->input('quantity');


        /*
        |--------------------------------------------------------------------------
        | CART
        |--------------------------------------------------------------------------
        */

        $cart =
            session()->get('cart', []);


        /*
        |--------------------------------------------------------------------------
        | CART ITEM EXISTS
        |--------------------------------------------------------------------------
        */

        if (!isset($cart[$bookId])) {

            return response()->json([
                'success' => false,
                'message' =>
                    'This book is not in your cart.',
            ], 404);
        }


        /*
        |--------------------------------------------------------------------------
        | FIND BOOK
        |--------------------------------------------------------------------------
        */

        $book =
            Book::find($bookId);


        /*
        |--------------------------------------------------------------------------
        | BOOK NO LONGER AVAILABLE
        |--------------------------------------------------------------------------
        */

        if (!$book || $book->status !== 'approved') {

            unset($cart[$bookId]);

            session()->put('cart', $cart);

            return response()->json([
                'success' => false,
                'message' =>
                    'This book is no longer available.',
                'removed' =>
                    true,
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | REMOVE ITEM WHEN QUANTITY IS ZERO
        |--------------------------------------------------------------------------
        */

        if ($quantity <= 0) {

            unset($cart[$bookId]);

            session()->put('cart', $cart);


            $remainingSubtotal =
                collect($cart)->sum(function ($item) {

                    return
                        (float) $item['price'] *
                        (int) $item['quantity'];

                });


            return response()->json([

                'success' =>
                    true,

                'message' =>
                    'Book removed from your cart.',

                'removed' =>
                    true,

                'total_items' =>
                    collect($cart)->sum('quantity'),

                'subtotal' =>
                    $remainingSubtotal,

            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | STOCK VALIDATION
        |--------------------------------------------------------------------------
        */

        if ($quantity > $book->stock) {

            return response()->json([
                'success' => false,
                'message' =>
                    'You cannot select more than the available stock.',
            ], 422);
        }


        /*
        |--------------------------------------------------------------------------
        | CURRENT BOOK PRICE
        |--------------------------------------------------------------------------
        |
        | Always recalculate the price from the database.
        | This prevents an old cart price from being used.
        |
        */

        $originalPrice =
            (float) $book->price;

        $currentPrice =
            $this->getActivePrice($book);

        $discountLabel =
            $this->getDiscountLabel($book);


        /*
        |--------------------------------------------------------------------------
        | UPDATE CART ITEM
        |--------------------------------------------------------------------------
        */

        $cart[$bookId]['id'] =
            $book->id;

        $cart[$bookId]['title'] =
            $book->title;

        $cart[$bookId]['original_price'] =
            $originalPrice;

        $cart[$bookId]['price'] =
            $currentPrice;

        $cart[$bookId]['discount_label'] =
            $discountLabel;

        $cart[$bookId]['cover'] =
            $book->cover;

        $cart[$bookId]['quantity'] =
            $quantity;

        $cart[$bookId]['stock'] =
            $book->stock;


        /*
        |--------------------------------------------------------------------------
        | SAVE CART
        |--------------------------------------------------------------------------
        */

        session()->put('cart', $cart);


        /*
        |--------------------------------------------------------------------------
        | RECALCULATE CART TOTALS
        |--------------------------------------------------------------------------
        */

        $totalItems =
            collect($cart)->sum('quantity');


        $subtotal =
            collect($cart)->sum(function ($item) {

                return
                    (float) $item['price'] *
                    (int) $item['quantity'];

            });


        /*
        |--------------------------------------------------------------------------
        | SHIPPING
        |--------------------------------------------------------------------------
        */

        $shippingEnabled =
            Setting::get(
                'shipping_enabled',
                true
            );


        $defaultShippingFee =
            (float) Setting::get(
                'default_shipping_fee',
                0
            );


        $freeShippingThreshold =
            (float) Setting::get(
                'free_shipping_threshold',
                0
            );


        $shippingFee =
            0;


        if ($shippingEnabled) {

            if (
                $freeShippingThreshold <= 0 ||
                $subtotal < $freeShippingThreshold
            ) {

                $shippingFee =
                    $defaultShippingFee;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | GRAND TOTAL
        |--------------------------------------------------------------------------
        */

        $grandTotal =
            $subtotal + $shippingFee;


        /*
        |--------------------------------------------------------------------------
        | CURRENT ITEM TOTAL
        |--------------------------------------------------------------------------
        */

        $itemTotal =
            (float) $cart[$bookId]['price'] *
            (int) $cart[$bookId]['quantity'];


        /*
        |--------------------------------------------------------------------------
        | AJAX RESPONSE
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'success' =>
                true,

            'message' =>
                'Cart updated successfully.',

            'item_total' =>
                $itemTotal,

            'total_items' =>
                $totalItems,

            'subtotal' =>
                $subtotal,

            'shipping_enabled' =>
                (bool) $shippingEnabled,

            'shipping_fee' =>
                $shippingFee,

            'grand_total' =>
                $grandTotal,

        ]);
    }


    /**
     * Remove a book from the cart.
     */
    public function remove($bookId)
    {
        if (!Setting::get('marketplace_enabled', true)) {
            abort(503);
        }


        $cart =
            session()->get('cart', []);


        if (!isset($cart[$bookId])) {

            return back()->with(
                'error',
                'This book is not in your cart.'
            );
        }


        unset($cart[$bookId]);


        session()->put(
            'cart',
            $cart
        );


        return back()->with(
            'success',
            'Book removed from your cart.'
        );
    }


    /**
     * Empty the entire cart.
     */
    public function clear()
    {
        if (!Setting::get('marketplace_enabled', true)) {
            abort(503);
        }


        session()->forget('cart');


        return redirect()
            ->route('frontend.cart')
            ->with(
                'success',
                'Your cart has been emptied.'
            );
    }


    /**
     * Get the currently active selling price.
     */
    private function getActivePrice(Book $book): float
    {
        $originalPrice =
            (float) $book->price;


        /*
        |--------------------------------------------------------------------------
        | NO DISCOUNT
        |--------------------------------------------------------------------------
        */

        if (
            ($book->discount_type ?? 'none') === 'none' ||
            (float) ($book->discount_value ?? 0) <= 0
        ) {
            return $originalPrice;
        }


        /*
        |--------------------------------------------------------------------------
        | DISCOUNT START DATE
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
        | DISCOUNT END DATE
        |--------------------------------------------------------------------------
        */

        if (
            $book->discount_end_at &&
            $book->discount_end_at->isPast()
        ) {
            return $originalPrice;
        }


        /*
        |--------------------------------------------------------------------------
        | DISCOUNT VALUE
        |--------------------------------------------------------------------------
        */

        $discountValue =
            (float) $book->discount_value;


        /*
        |--------------------------------------------------------------------------
        | PERCENTAGE DISCOUNT
        |--------------------------------------------------------------------------
        */

        if (
            $book->discount_type === 'percentage'
        ) {

            $discountValue =
                min(
                    max($discountValue, 0),
                    100
                );


            $discountAmount =
                $originalPrice *
                ($discountValue / 100);


            return round(
                max(
                    0,
                    $originalPrice - $discountAmount
                ),
                2
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FIXED DISCOUNT
        |--------------------------------------------------------------------------
        */

        if (
            $book->discount_type === 'fixed'
        ) {

            return round(
                max(
                    0,
                    $originalPrice - $discountValue
                ),
                2
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FALLBACK
        |--------------------------------------------------------------------------
        */

        return $originalPrice;
    }


    /**
     * Get the display label for the active discount.
     */
    private function getDiscountLabel(Book $book): ?string
    {
        /*
        |--------------------------------------------------------------------------
        | CHECK ACTIVE DISCOUNT
        |--------------------------------------------------------------------------
        */

        if (
            ($book->discount_type ?? 'none') === 'none' ||
            (float) ($book->discount_value ?? 0) <= 0
        ) {
            return null;
        }


        /*
        |--------------------------------------------------------------------------
        | START DATE
        |--------------------------------------------------------------------------
        */

        if (
            $book->discount_start_at &&
            $book->discount_start_at->isFuture()
        ) {
            return null;
        }


        /*
        |--------------------------------------------------------------------------
        | END DATE
        |--------------------------------------------------------------------------
        */

        if (
            $book->discount_end_at &&
            $book->discount_end_at->isPast()
        ) {
            return null;
        }


        /*
        |--------------------------------------------------------------------------
        | PERCENTAGE
        |--------------------------------------------------------------------------
        */

        if (
            $book->discount_type === 'percentage'
        ) {

            return rtrim(
                rtrim(
                    number_format(
                        (float) $book->discount_value,
                        2,
                        '.',
                        ''
                    ),
                    '0'
                ),
                '.'
            ) . '% OFF';
        }


        /*
        |--------------------------------------------------------------------------
        | FIXED
        |--------------------------------------------------------------------------
        */

        if (
            $book->discount_type === 'fixed'
        ) {

            return '$' .
                number_format(
                    (float) $book->discount_value,
                    2
                ) .
                ' OFF';
        }


        return null;
    }
}

