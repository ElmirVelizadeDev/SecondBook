<?php

use Illuminate\Support\Facades\Route;

// =========================================================
// ADMIN CONTROLLERS
// =========================================================

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\BooksController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AuthorsController;
use App\Http\Controllers\Admin\PublishersController;
use App\Http\Controllers\Admin\BookConditionController;
use App\Http\Controllers\Admin\BookRequestController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\CouponsController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\RefundsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\SellersController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\MessagesController;
use App\Http\Controllers\Admin\ReviewsController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\EmailSettingsController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\SellerApplicationsController;

// =========================================================
// FRONTEND CONTROLLERS
// =========================================================

use App\Http\Controllers\Frontend\BooksController as FrontendBooksController;
use App\Http\Controllers\Frontend\CategoriesController as FrontendCategoriesController;
use App\Http\Controllers\Frontend\AuthorsController as FrontendAuthorsController;
use App\Http\Controllers\Frontend\FaqController as FrontendFaqController;
use App\Http\Controllers\Frontend\OrdersController as FrontendOrdersController;
use App\Http\Controllers\Frontend\PaymentController as FrontendPaymentController;
use App\Http\Controllers\Frontend\NotificationController as FrontendNotificationController;
use App\Http\Controllers\Frontend\StoreController as FrontendStoreController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ShippingInformationController;
use App\Http\Controllers\Frontend\HelpCenterController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\AccountSettingsController;
use App\Http\Controllers\Frontend\SellerApplicationController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\ReturnPolicyController;
use App\Http\Controllers\Frontend\CookiePolicyController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\PrivacyPolicyController;

// =========================================================
// SELLER CONTROLLERS
// =========================================================

use App\Http\Controllers\Seller\SellerDashboardController;
use App\Http\Controllers\Seller\StoreController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\BookController;
use App\Http\Controllers\Seller\SalesController;
use App\Http\Controllers\Seller\ReviewController as SellerReviewController;
use App\Http\Controllers\Seller\StoreSettingsController;
use App\Http\Controllers\Seller\MessagesController as SellerMessagesController;
use App\Http\Controllers\Seller\AnalyticsController as SellerAnalyticsController;
use App\Http\Controllers\Seller\NotificationController as SellerNotificationController;

// =========================================================
// AUTH CONTROLLER
// =========================================================

use App\Http\Controllers\Auth\AuthController;

// =========================================================
// ROOT
// =========================================================

Route::redirect('/', '/frontend');

// =========================================================
// ADMIN PANEL
// =========================================================

Route::prefix('admin')
    ->middleware(['admin', 'permission'])
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        // BOOKS
        Route::controller(BooksController::class)
            ->prefix('books')
            ->name('books.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{book}', 'show')->name('show');
                Route::get('/{book}/edit', 'edit')->name('edit');
                Route::put('/{book}', 'update')->name('update');
                Route::delete('/{book}', 'destroy')->name('destroy');
            });

        // BOOK CONDITIONS
        Route::controller(BookConditionController::class)
            ->prefix('book-conditions')
            ->name('book.conditions.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{condition}/edit', 'edit')->name('edit');
                Route::patch('/{condition}/status', 'status')->name('status');
                Route::put('/{condition}', 'update')->name('update');
                Route::delete('/{condition}', 'destroy')->name('destroy');
            });

        // BOOK REQUESTS
        Route::controller(BookRequestController::class)
            ->prefix('book-requests')
            ->name('book.requests.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::get('/{book}/edit', 'edit')->name('edit');
                Route::put('/{book}', 'update')->name('update');
                Route::delete('/{book}', 'destroy')->name('destroy');
            });

        // CATEGORIES
        Route::controller(CategoryController::class)
            ->prefix('categories')
            ->name('categories.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::patch('/{category}/status', 'toggleStatus')->name('status');
                Route::get('/{category}', 'show')->name('show');
                Route::get('/{category}/edit', 'edit')->name('edit');
                Route::put('/{category}', 'update')->name('update');
                Route::delete('/{category}', 'destroy')->name('destroy');
            });

        // AUTHORS
        Route::controller(AuthorsController::class)
            ->prefix('authors')
            ->name('authors.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::patch('/{author}/status', 'toggleStatus')->name('status');
                Route::get('/{author}', 'show')->name('show');
                Route::get('/{author}/edit', 'edit')->name('edit');
                Route::put('/{author}', 'update')->name('update');
                Route::delete('/{author}', 'destroy')->name('destroy');
            });

        // PUBLISHERS
        Route::controller(PublishersController::class)
            ->prefix('publishers')
            ->name('publishers.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::patch('/{publisher}/status', 'toggleStatus')->name('status');
                Route::get('/{publisher}', 'show')->name('show');
                Route::get('/{publisher}/edit', 'edit')->name('edit');
                Route::put('/{publisher}', 'update')->name('update');
                Route::delete('/{publisher}', 'destroy')->name('destroy');
            });

        // REFUNDS
        Route::controller(RefundsController::class)
            ->prefix('refunds')
            ->name('refunds.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{refund}/edit', 'edit')->name('edit');
                Route::patch('/{refund}/status', 'updateStatus')->name('status');
                Route::get('/{refund}', 'show')->name('show');
                Route::put('/{refund}', 'update')->name('update');
                Route::delete('/{refund}', 'destroy')->name('destroy');
            });

        // SHIPPING
        Route::controller(ShippingController::class)
            ->prefix('shipping')
            ->name('shipping.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{shipping}/edit', 'edit')->name('edit');
                Route::put('/{shipping}', 'update')->name('update');
                Route::delete('/{shipping}', 'destroy')->name('destroy');
            });

        // COUPONS
        Route::controller(CouponsController::class)
            ->prefix('coupons')
            ->name('coupons.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{coupon}', 'show')->name('show');
                Route::get('/{coupon}/edit', 'edit')->name('edit');
                Route::put('/{coupon}', 'update')->name('update');
                Route::patch('/{coupon}/toggle-status', 'toggleStatus')->name('toggle-status');
                Route::delete('/{coupon}', 'destroy')->name('destroy');
            });

        // ORDERS
        Route::controller(OrdersController::class)
            ->prefix('orders')
            ->name('orders.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{order}', 'show')->name('show');
                Route::get('/{order}/edit', 'edit')->name('edit');
                Route::put('/{order}', 'update')->name('update');
                Route::delete('/{order}', 'destroy')->name('destroy');
            });

        // PAYMENTS
        Route::controller(PaymentsController::class)
            ->prefix('payments')
            ->name('payments.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{payment}', 'show')->name('show');
                Route::get('/{payment}/edit', 'edit')->name('edit');
                Route::put('/{payment}', 'update')->name('update');
                Route::delete('/{payment}', 'destroy')->name('destroy');
            });

        // SELLER APPLICATIONS - ADMIN MANAGEMENT
        Route::controller(SellerApplicationsController::class)
            ->prefix('seller-applications')
            ->name('seller-applications.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{application}', 'show')->name('show');
                Route::patch('/{application}/approve', 'approve')->name('approve');
                Route::patch('/{application}/reject', 'reject')->name('reject');
            });

        // USERS
        Route::controller(UsersController::class)
            ->prefix('users')
            ->name('users.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::patch('/{user}/status', 'updateStatus')->name('status');
                Route::get('/{user}', 'show')->name('show');
                Route::get('/{user}/edit', 'edit')->name('edit');
                Route::put('/{user}', 'update')->name('update');
                Route::delete('/{user}', 'destroy')->name('destroy');
            });

        // SELLERS
        Route::controller(SellersController::class)
            ->prefix('sellers')
            ->name('sellers.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::patch('/{seller}/status', 'updateStatus')->name('status');
                Route::get('/{seller}', 'show')->name('show');
                Route::get('/{seller}/edit', 'edit')->name('edit');
                Route::put('/{seller}', 'update')->name('update');
                Route::delete('/{seller}', 'destroy')->name('destroy');
            });

        // ROLES
        Route::controller(RoleController::class)
            ->prefix('roles')
            ->name('roles.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{role}', 'show')->name('show');
                Route::get('/{role}/edit', 'edit')->name('edit');
                Route::put('/{role}', 'update')->name('update');
                Route::delete('/{role}', 'destroy')->name('destroy');
            });

        // REVIEWS
        Route::controller(ReviewsController::class)
            ->prefix('reviews')
            ->name('reviews.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{review}', 'show')->name('show');
                Route::patch('/{review}/approve', 'approve')->name('approve');
                Route::patch('/{review}/reject', 'reject')->name('reject');
                Route::delete('/{review}', 'destroy')->name('destroy');
            });

        // ADMIN MESSAGES
        Route::controller(MessagesController::class)
            ->prefix('messages')
            ->name('messages.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{message}/reply', 'reply')->name('reply');
                Route::post('/{message}/reply', 'sendReply')->name('sendReply');
                Route::get('/{message}/site-reply', 'siteReply')->name('site-reply');
                Route::post('/{message}/site-reply', 'sendSiteReply')->name('send-site-reply');
                Route::patch('/{message}/unread', 'markAsUnread')->name('unread');
                Route::get('/{message}', 'show')->name('show');
                Route::delete('/{message}', 'destroy')->name('destroy');
            });

        // BANNERS
        Route::controller(BannerController::class)
            ->prefix('banners')
            ->name('banners.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{banner}/edit', 'edit')->name('edit');
                Route::put('/{banner}', 'update')->name('update');
                Route::delete('/{banner}', 'destroy')->name('destroy');
            });

        // BLOGS
        Route::controller(BlogController::class)
            ->prefix('blogs')
            ->name('blogs.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{blog}/edit', 'edit')->name('edit');
                Route::put('/{blog}', 'update')->name('update');
                Route::delete('/{blog}', 'destroy')->name('destroy');
            });

        // FAQ
        Route::controller(FaqController::class)
            ->prefix('faq')
            ->name('faq.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{faq}/edit', 'edit')->name('edit');
                Route::put('/{faq}', 'update')->name('update');
                Route::delete('/{faq}', 'destroy')->name('destroy');
            });

        // REPORTS
        Route::controller(ReportsController::class)
            ->prefix('reports')
            ->name('reports.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/sales', 'sales')->name('sales');
                Route::get('/books', 'books')->name('books');
            });

        // ANALYTICS
        Route::get('/analytics', [AnalyticsController::class, 'index'])
            ->name('analytics.index');

        // SETTINGS
        Route::controller(SettingsController::class)
            ->prefix('settings')
            ->name('settings.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::put('/', 'update')->name('update');
            });

        // EMAIL SETTINGS
        Route::controller(EmailSettingsController::class)
            ->prefix('email-settings')
            ->name('email-settings.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::put('/', 'update')->name('update');
                Route::post('/test', 'test')->name('test');
            });

        // ADMIN NOTIFICATIONS
        Route::controller(NotificationController::class)
            ->prefix('notifications')
            ->name('notifications.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/send', 'send')->name('send');
                Route::post('/read-all', 'markAllAsRead')->name('read-all');
                Route::post('/{notification}/read', 'markAsRead')->name('read');
                Route::post('/{notification}/unread', 'markAsUnread')->name('unread');
                Route::delete('/{notification}', 'destroy')->name('destroy');
            });

        // ACTIVITY LOGS
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])
            ->name('activity.logs.index');

        // BACKUP
        Route::controller(BackupController::class)
            ->prefix('backup')
            ->name('backup.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/create', 'create')->name('create');
                Route::get('/download/{file}', 'download')->name('download');
                Route::delete('/delete/{file}', 'delete')->name('delete');
                Route::post('/restore/{file}', 'restore')->name('restore');
            });
    });

// =========================================================
// PUBLIC STORE
// =========================================================

Route::get('/store/{store:slug}', [FrontendStoreController::class, 'show'])
    ->name('store.show');

Route::post('/store/{store:slug}/contact', [ContactController::class, 'sendStoreMessage'])
    ->name('store.contact.send');

// =========================================================
// SELLER PANEL
// =========================================================

Route::prefix('seller')
    ->middleware(['auth', 'seller'])
    ->name('seller.')
    ->group(function () {

        Route::get('/dashboard', [SellerDashboardController::class, 'index'])
            ->name('dashboard');

        // STORE
        Route::get('/store', [StoreController::class, 'edit'])->name('store');
        Route::put('/store', [StoreController::class, 'update'])->name('store.update');

        // BOOKS
        Route::get('/books', [BookController::class, 'index'])->name('books.index');
        Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
        Route::post('/books', [BookController::class, 'store'])->name('books.store');
        Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
        Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
        Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');

        // ORDERS
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');

        // SALES
        Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');

        // REVIEWS
        Route::get('/reviews', [SellerReviewController::class, 'index'])->name('reviews.index');
        Route::get('/reviews/{review}', [SellerReviewController::class, 'show'])->name('reviews.show');

        // ANALYTICS
        Route::get('/analytics', [SellerAnalyticsController::class, 'index'])->name('analytics.index');

        // MESSAGES
        Route::controller(SellerMessagesController::class)
            ->prefix('messages')
            ->name('messages.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/{message}/read', 'markAsRead')->name('read');
                Route::post('/{message}/unread', 'markAsUnread')->name('unread');
                Route::post('/{message}/reply', 'reply')->name('reply');
                Route::delete('/{messageId}/replies/{replyId}', 'deleteReply')->name('delete-reply');
                Route::get('/{message}', 'show')->name('show');
            });

        // SELLER NOTIFICATIONS
        Route::controller(SellerNotificationController::class)
            ->prefix('notifications')
            ->name('notifications.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/read-all', 'markAllAsRead')->name('read-all');
                Route::delete('/destroy-all', 'destroyAll')->name('destroy-all');
                Route::post('/{notification}/read', 'markAsRead')->name('read');
                Route::post('/{notification}/unread', 'markAsUnread')->name('unread');
                Route::delete('/{notification}', 'destroy')->name('destroy');
            });

        // SETTINGS
        Route::get('/settings', [StoreSettingsController::class, 'index'])->name('settings');
        Route::put('/settings', [StoreSettingsController::class, 'update'])->name('settings.update');
    });

// =========================================================
// FRONTEND
// =========================================================

Route::prefix('frontend')
    ->name('frontend.')
    ->group(function () {

        // HOME
        Route::controller(HomeController::class)->group(function () {
            Route::get('/', 'index')->name('home');
            Route::get('/featured-books', 'featuredBooks')->name('featured-books');
        });

        // BOOKS
        Route::controller(FrontendBooksController::class)->group(function () {
            Route::get('/books', 'index')->name('books');
            Route::get('/books/{book}', 'show')->name('books.show');
        });

        // CATEGORIES
        Route::get('/categories', [FrontendCategoriesController::class, 'index'])
            ->name('categories');

        // AUTHORS
        Route::get('/authors', [FrontendAuthorsController::class, 'index'])
            ->name('authors');

        // ABOUT
        Route::get('/about', function () {
            return view('Frontend.about');
        })->name('about');

        // CONTACT
        Route::controller(ContactController::class)->group(function () {
            Route::get('/contact', 'index')->name('contact');
            Route::post('/contact', 'store')->name('contact.store');
        });

        // =====================================================
        // BECOME A SELLER
        // Only normal users can access these routes.
        // The middleware protects both GET and POST requests.
        // =====================================================

        Route::controller(SellerApplicationController::class)
            ->middleware('restrict.seller.application')
            ->group(function () {
                Route::get('/become-a-seller', 'create')
                    ->name('seller-application');

                Route::post('/become-a-seller', 'store')
                    ->name('seller-application.store');
            });

        // FAQ
        Route::get('/faq', [FrontendFaqController::class, 'index'])
            ->name('faq');

        // HELP CENTER
        Route::get('/help-center', [HelpCenterController::class, 'index'])
            ->name('help-center');

        // SHIPPING INFORMATION
        Route::get('/shipping-information', [ShippingInformationController::class, 'index'])
            ->name('shipping-information');

        // RETURN POLICY
        Route::get('/return-policy', [ReturnPolicyController::class, 'index'])
            ->name('return-policy');

        // PRIVACY POLICY
        Route::get('/privacy-policy', [PrivacyPolicyController::class, 'index'])
            ->name('privacy-policy');

        // COOKIES POLICY
        Route::get('/cookies', [CookiePolicyController::class, 'index'])
            ->name('cookies');

        // AUTHENTICATION - GUEST
        Route::controller(AuthController::class)
            ->prefix('auth')
            ->name('auth.')
            ->middleware('guest')
            ->group(function () {

                // LOGIN
                Route::get('/login', 'login')->name('login');
                Route::post('/login', 'storeLogin')->name('login.store');

                // REGISTER
                Route::get('/register', 'register')->name('register');
                Route::post('/register', 'storeRegister')->name('register.store');

                // GOOGLE LOGIN
                Route::get('/google', 'redirectToGoogle')->name('google.redirect');
                Route::get('/google/callback', 'handleGoogleCallback')->name('google.callback');

                // ADMIN TWO-FACTOR AUTHENTICATION
                Route::get('/admin/2fa', 'showAdminTwoFactor')->name('admin.2fa');
                Route::post('/admin/2fa', 'verifyAdminTwoFactor')->name('admin.2fa.verify');

                // FORGOT PASSWORD
                Route::get('/password/request', function () {
                    session()->forget('reset_email');

                    return view('auth.password-request');
                })->name('password.request');

                // SEND OTP
                Route::post('/forgot-password/send-otp', 'sendOtp')
                    ->name('password.send.otp');
            });

        // EMAIL VERIFICATION
        Route::get('/auth/email/verify', [AuthController::class, 'emailVerify'])
            ->name('auth.email.verify');

        Route::post('/auth/email/verify', [AuthController::class, 'verifyEmailOtp'])
            ->name('auth.email.verify.otp');

        Route::post('/auth/email/resend', [AuthController::class, 'resendEmailVerificationOtp'])
            ->name('auth.email.verify.resend');

        // PASSWORD VERIFICATION PAGE
        Route::get('/auth/password/verify', function () {
            $resetEmail = session('reset_email');
            $sensitiveAction = session('sensitive_action');
            $sensitiveEmail = session('sensitive_action_email');

            $hasSensitiveVerification =
                in_array($sensitiveAction, ['password_change'], true)
                && $sensitiveEmail;

            if (!$resetEmail && !$hasSensitiveVerification) {
                return redirect()->route('frontend.auth.password.request');
            }

            return view('auth.password-verify');
        })->name('auth.password.verify');

        // RESEND PASSWORD VERIFICATION OTP
        Route::post('/auth/password/resend', [AuthController::class, 'resendVerificationCode'])
            ->name('auth.password.resend');

        // VERIFY PASSWORD OTP
        Route::post('/auth/password/verify', [AuthController::class, 'verifyOtp'])
            ->name('auth.password.verify.otp');

        // ACCOUNT DELETE VERIFICATION
        Route::middleware('auth')->group(function () {
            Route::get('/auth/account/delete/verify', [AuthController::class, 'accountDeleteVerify'])
                ->name('auth.account.delete.verify');

            Route::post('/auth/account/delete/verify-password', [AuthController::class, 'verifyAccountDeletePassword'])
                ->name('auth.account.delete.verify.password');

            Route::post('/auth/account/delete/verify-otp', [AuthController::class, 'verifyAccountDeleteOtp'])
                ->name('auth.account.delete.verify.otp');

            Route::post('/auth/account/delete/resend', [AuthController::class, 'resendVerificationCode'])
                ->name('auth.account.delete.resend');
        });

        // TERMS
        Route::get('/auth/terms', function () {
            return view('auth.terms');
        })->name('auth.terms');

        // LOGGED USERS - AUTHENTICATION
        Route::controller(AuthController::class)
            ->prefix('auth')
            ->name('auth.')
            ->middleware('auth')
            ->group(function () {
                Route::post('/logout', 'logout')->name('logout');
            });
    });

// =========================================================
// PROFILE
// =========================================================

Route::middleware('auth')->group(function () {
    Route::get('/my-profile', [AuthController::class, 'myprofile'])->name('my.profile');
    Route::get('/profile/edit', [AuthController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::delete('/profile/photo', [AuthController::class, 'removeProfilePhoto'])->name('profile.photo.destroy');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [AuthController::class, 'destroyProfile'])->name('profile.destroy');
});

// =========================================================
// FRONTEND CUSTOMER ACCOUNT
// =========================================================

Route::middleware('auth')
    ->prefix('frontend')
    ->name('frontend.')
    ->group(function () {

        // CART
        Route::get('/cart', [CartController::class, 'index'])->name('cart');
        Route::post('/cart/add/{book}', [CartController::class, 'add'])->name('cart.add');
        Route::patch('/cart/update/{book}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/remove/{book}', [CartController::class, 'remove'])->name('cart.remove');
        Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

        // CHECKOUT
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

        // PAYMENTS
        Route::get('/payment/{order}', [FrontendPaymentController::class, 'show'])->name('payment');
        Route::post('/payment/{order}', [FrontendPaymentController::class, 'process'])->name('payment.process');

        // NOTIFICATIONS
        Route::controller(FrontendNotificationController::class)
            ->prefix('notifications')
            ->name('notifications.')
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/{notification}/read', 'markAsRead')->name('read');
                Route::post('/read-all', 'markAllAsRead')->name('read-all');
                Route::delete('/{notification}', 'destroy')->name('destroy');
            });

        // WISHLIST
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
        Route::post('/wishlist/add/{book}', [WishlistController::class, 'add'])->name('wishlist.add');
        Route::delete('/wishlist/remove/{book}', [WishlistController::class, 'remove'])->name('wishlist.remove');

        // ORDERS
        Route::get('/orders', [FrontendOrdersController::class, 'index'])->name('orders');
        Route::get('/orders/{order}', [FrontendOrdersController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/cancel', [FrontendOrdersController::class, 'cancel'])->name('orders.cancel');

        // REVIEWS
        Route::get('/orders/{order}/review', [ReviewController::class, 'create'])->name('reviews.create');
        Route::post('/orders/{order}/review', [ReviewController::class, 'store'])->name('reviews.store');

        // ORDER TRACKING
        Route::get('/order-tracking/{order}', [FrontendOrdersController::class, 'tracking'])
            ->name('order-tracking');
    });

// =========================================================
// FRONTEND ACCOUNT SETTINGS
// =========================================================

Route::middleware('auth')
    ->prefix('frontend/account')
    ->name('frontend.account.')
    ->group(function () {

        Route::get('/settings', [AccountSettingsController::class, 'index'])
            ->name('settings');

        Route::put('/settings/preferences', [AccountSettingsController::class, 'updatePreferences'])
            ->name('settings.preferences');

        Route::put('/settings/password', [AccountSettingsController::class, 'updatePassword'])
            ->name('settings.password');
    });
