<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = (string) $request->route()?->getName();

        if (!str_starts_with($routeName, 'admin.')) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        if ($routeName === 'admin.dashboard') {
            return $next($request);
        }

        $permission = $this->permissionForRoute(
            $routeName,
            $request
        );

        /*
        |--------------------------------------------------------------------------
        | Permission Check
        |--------------------------------------------------------------------------
        */

        if (
            $permission === null ||
            $request->user()?->hasPermission($permission)
        ) {
            return $next($request);
        }

        /*
        |--------------------------------------------------------------------------
        | Access Denied
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->back()
            ->with(
                'permission_denied',
                'You do not have permission to access this page.'
            );
    }

    private function permissionForRoute(
        string $routeName,
        Request $request
    ): ?string {
        $routeName = substr($routeName, strlen('admin.'));

        /*
        |--------------------------------------------------------------------------
        | Module Mapping
        |--------------------------------------------------------------------------
        */

        $module = match (true) {

            str_starts_with($routeName, 'book.conditions.')
                => 'book_conditions',

            str_starts_with($routeName, 'book.requests.')
                => 'book_requests',

            str_starts_with($routeName, 'email-settings.')
                => 'email_settings',

            str_starts_with($routeName, 'activity.logs.')
                => 'activity_logs',

            str_starts_with($routeName, 'roles.')
                => 'roles',

            str_starts_with($routeName, 'faq.')
                => 'faq',

            str_starts_with($routeName, 'seller-applications.')
                => 'seller_applications',

            str_starts_with($routeName, 'backup.')
                => 'backup',

            default => str($routeName)
                ->before('.')
                ->toString(),
        };

        /*
        |--------------------------------------------------------------------------
        | Special Actions
        |--------------------------------------------------------------------------
        */

        $specialPermissions = [

            /*
            | Reviews
            */
            'reviews.approve' => 'reviews.approve',
            'reviews.reject'  => 'reviews.reject',

            /*
            | Seller Applications
            */
            'seller-applications.approve'
                => 'seller_applications.approve',

            'seller-applications.reject'
                => 'seller_applications.reject',

            /*
            | Notifications
            */
            'notifications.read'      => 'notifications.view',
            'notifications.read-all'  => 'notifications.view',
            'notifications.unread'    => 'notifications.view',

            /*
            | Messages
            */
            'messages.reply'          => 'messages.create',
            'messages.sendReply'      => 'messages.create',
            'messages.site-reply'     => 'messages.create',
            'messages.send-site-reply'=> 'messages.create',
            'messages.unread'         => 'messages.view',

            /*
            | Backup
            */
            'backup.create'   => 'backup.create',
            'backup.download' => 'backup.view',
            'backup.delete'   => 'backup.delete',
            'backup.restore'  => 'backup.create',
        ];

        if (isset($specialPermissions[$routeName])) {
            return $specialPermissions[$routeName];
        }

        /*
        |--------------------------------------------------------------------------
        | Action
        |--------------------------------------------------------------------------
        */

        $action = str($routeName)
            ->afterLast('.')
            ->toString();

        /*
        |--------------------------------------------------------------------------
        | Status / Toggle Status
        |--------------------------------------------------------------------------
        */

        if (
            $action === 'status' ||
            $action === 'toggle-status'
        ) {
            if ($module === 'refunds') {

                return in_array(
                    $request->input('status'),
                    ['approved', 'rejected'],
                    true
                )
                    ? 'refunds.approve'
                    : (
                        $request->input('status') === 'processed'
                            ? 'refunds.process'
                            : 'refunds.edit'
                    );
            }

            return in_array(
                $module,
                ['users', 'sellers'],
                true
            )
                ? $module . '.ban'
                : $module . '.edit';
        }

        /*
        |--------------------------------------------------------------------------
        | Standard CRUD Actions
        |--------------------------------------------------------------------------
        */

        return match (true) {

            in_array(
                $action,
                [
                    'index',
                    'show',
                    'sales',
                    'users',
                    'books',
                    'download',
                ],
                true
            )
                => $module . '.view',

            in_array(
                $action,
                [
                    'create',
                    'store',
                ],
                true
            )
                => $module . '.create',

            in_array(
                $action,
                [
                    'edit',
                    'update',
                ],
                true
            )
                => $module . '.edit',

            in_array(
                $action,
                [
                    'destroy',
                    'delete',
                ],
                true
            )
                => $module . '.delete',

            default
                => $module . '.view',
        };
    }
}