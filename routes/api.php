<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\StoreController as ApiStoreController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\WishlistController as ApiWishlistController;
use App\Http\Controllers\Api\AccountController as ApiAccountController;
use App\Http\Controllers\Api\OrderController as ApiOrderController;
use App\Http\Controllers\Api\PaymentController as ApiPaymentController;
use App\Http\Controllers\Api\ContactController as ApiContactController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\OrdersController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\AccountSettingsController;
use App\Http\Controllers\Frontend\SellerApplicationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/books', [BookController::class, 'index']);

Route::get('/home', [HomeController::class, 'index']);
Route::get('/books/{book}', [BookController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);
Route::get('/authors', [AuthorController::class, 'index']);
Route::get('/authors/{author}', [AuthorController::class, 'show']);
Route::get('/stores/{store:slug}', [ApiStoreController::class, 'show']);
Route::get('/site-settings', [ContentController::class, 'siteSettings']);
Route::get('/faqs', [ContentController::class, 'faqs']);
Route::get('/banners', [ContentController::class, 'banners']);
Route::get('/publishers', [ContentController::class, 'publishers']);
Route::get('/shipping-information', [ContentController::class, 'shipping']);

Route::middleware('web')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'storeLogin'])
        ->middleware('guest');
    Route::post('/auth/register', [AuthController::class, 'storeRegister'])
        ->middleware(['guest', 'throttle:5,1']);
    Route::post('/auth/email/verify', [AuthController::class, 'verifyEmailOtp'])
        ->middleware('throttle:5,1');
    Route::post('/auth/email/resend', [AuthController::class, 'resendVerificationCode'])
        ->middleware('throttle:3,1');
    Route::post('/auth/password/send-otp', [AuthController::class, 'sendOtp'])
        ->middleware(['guest', 'throttle:3,1']);
    Route::post('/auth/password/resend', [AuthController::class, 'resendVerificationCode'])
        ->middleware('throttle:3,1');
    Route::post('/auth/password/verify', [AuthController::class, 'verifyOtp'])
        ->middleware('throttle:5,1');

    Route::post('/contact', [ApiContactController::class, 'store'])
        ->middleware('throttle:10,1');

    Route::middleware('auth')->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/user', [ApiAccountController::class, 'user']);
        Route::get('/me', [ApiAccountController::class, 'user']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
        Route::delete('/profile/photo', [AuthController::class, 'removeProfilePhoto']);
        Route::post('/account/delete/request', [AuthController::class, 'destroyProfile'])
            ->middleware('throttle:3,1');
        Route::post('/account/delete/verify-password', [AuthController::class, 'verifyAccountDeletePassword'])
            ->middleware('throttle:5,1');
        Route::post('/account/delete/resend', [AuthController::class, 'resendVerificationCode'])
            ->middleware('throttle:3,1');
        Route::post('/account/delete/verify-otp', [AuthController::class, 'verifyAccountDeleteOtp'])
            ->middleware('throttle:5,1');

        Route::get('/cart', [CartController::class, 'index']);
        Route::post('/cart/items/{book}', [CartController::class, 'add']);
        Route::patch('/cart/items/{book}', [CartController::class, 'update']);
        Route::delete('/cart/items/{book}', [CartController::class, 'remove']);
        Route::delete('/cart', [CartController::class, 'clear']);
        Route::get('/checkout', [CheckoutController::class, 'index']);
        Route::post('/checkout', [CheckoutController::class, 'store']);

        Route::get('/wishlist', [ApiWishlistController::class, 'index']);
        Route::get('/wishlist/{book}/state', [ApiWishlistController::class, 'state']);
        Route::post('/wishlist/{book}', [WishlistController::class, 'add']);
        Route::delete('/wishlist/{book}', [WishlistController::class, 'remove']);

        Route::get('/orders', [ApiOrderController::class, 'index']);
        Route::get('/orders/{order}', [ApiOrderController::class, 'show']);
        Route::get('/orders/{order}/tracking', [ApiOrderController::class, 'tracking']);
        Route::post('/orders/{order}/cancel', [OrdersController::class, 'cancel']);
        Route::get('/orders/{order}/review', [ReviewController::class, 'create']);
        Route::post('/orders/{order}/review', [ReviewController::class, 'store']);
        Route::get('/payments/{order}', [ApiPaymentController::class, 'show']);

        Route::get('/account/preferences', [ApiAccountController::class, 'preferences']);
        Route::put('/account/preferences', [AccountSettingsController::class, 'updatePreferences']);
        Route::post('/account/password/change-request', [AccountSettingsController::class, 'updatePassword']);

        Route::get('/notifications', [ApiAccountController::class, 'notifications']);
        Route::post('/notifications/{notification}/read', [ApiAccountController::class, 'markNotificationRead']);
        Route::post('/notifications/read-all', [ApiAccountController::class, 'markAllNotificationsRead']);
        Route::delete('/notifications/{notification}', [ApiAccountController::class, 'deleteNotification']);

        Route::post('/stores/{store:slug}/messages', [ApiContactController::class, 'storeMessage'])
            ->middleware('throttle:10,1');
        Route::get('/seller-application', [ApiAccountController::class, 'sellerApplication']);
        Route::post('/seller-application', [SellerApplicationController::class, 'store'])
            ->middleware('restrict.seller.application');
    });
});