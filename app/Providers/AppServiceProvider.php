<?php

namespace App\Providers;

use App\Models\Message;
use App\Models\Notification;
use App\Models\SellerApplication;
use App\Services\MailConfigurationService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(
        MailConfigurationService $mailConfigurationService
    ): void {
        Paginator::useBootstrapFive();

        $mailConfigurationService->apply();

        view()->composer('layout.admin.master', function ($view) {
            /*
             * Unread Messages
             */
            $unreadMessagesCount = Message::where(
                'status',
                'unread'
            )->count();

            /*
             * Unread Notifications
             */
            $unreadNotificationsCount = 0;

            if (auth()->check()) {
                $unreadNotificationsCount = Notification::where(
                    'user_id',
                    auth()->id()
                )
                    ->whereNull('read_at')
                    ->count();
            }

            /*
             * Pending Seller Applications
             */
            $pendingSellerApplicationsCount = SellerApplication::where(
                'status',
                'pending'
            )->count();

            /*
             * Share data with admin layout
             */
            $view->with([
                'unreadMessagesCount' => $unreadMessagesCount,
                'unreadNotificationsCount' => $unreadNotificationsCount,
                'pendingSellerApplicationsCount' => $pendingSellerApplicationsCount,
            ]);
        });
    }
}
