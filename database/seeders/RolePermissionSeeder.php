<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        $modules = [
            'dashboard' => [
                'view',
            ],

            'books' => [
                'view',
                'create',
                'edit',
                'delete',
                'approve',
                'reject',
            ],

            'book_conditions' => [
                'view',
                'create',
                'edit',
                'delete',
            ],

            'book_requests' => [
                'view',
                'create',
                'edit',
                'delete',
            ],

            'categories' => [
                'view',
                'create',
                'edit',
                'delete',
            ],

            'authors' => [
                'view',
                'create',
                'edit',
                'delete',
            ],

            'publishers' => [
                'view',
                'create',
                'edit',
                'delete',
            ],

            'refunds' => [
                'view',
                'create',
                'edit',
                'delete',
                'approve',
                'process',
            ],

            'shipping' => [
                'view',
                'create',
                'edit',
                'delete',
            ],

            'coupons' => [
                'view',
                'create',
                'edit',
                'delete',
            ],

            'orders' => [
                'view',
                'create',
                'edit',
                'delete',
                'update_status',
            ],

            'payments' => [
                'view',
                'create',
                'edit',
                'delete',
                'refund',
            ],

            'users' => [
                'view',
                'create',
                'edit',
                'delete',
                'ban',
            ],

            'sellers' => [
                'view',
                'create',
                'edit',
                'delete',
                'ban',
            ],

            'seller_applications' => [
                'view',
                'approve',
                'reject',
            ],

            'reviews' => [
                'view',
                'edit',
                'delete',
                'approve',
            ],

            'messages' => [
                'view',
                'create',
                'delete',
            ],

            'banners' => [
                'view',
                'create',
                'edit',
                'delete',
            ],

            'blogs' => [
                'view',
                'create',
                'edit',
                'delete',
            ],

            'faq' => [
                'view',
                'create',
                'edit',
                'delete',
            ],

            'reports' => [
                'view',
            ],

            'analytics' => [
                'view',
            ],

            'settings' => [
                'view',
                'edit',
            ],

            'email_settings' => [
                'view',
                'edit',
            ],

            'notifications' => [
                'view',
                'create',
                'delete',
            ],

            'activity_logs' => [
                'view',
            ],

            'backup' => [
                'view',
                'create',
            ],

            'roles' => [
                'view',
                'create',
                'edit',
                'delete',
            ],

            'permissions' => [
                'view',
                'assign',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Create / Update Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = collect();

        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                $name = $module . '.' . $action;

                $permission = Permission::updateOrCreate(
                    [
                        'name' => $name,
                    ],
                    [
                        'display_name' => $this->displayName(
                            $module,
                            $action
                        ),

                        'group_name' => $this->groupName(
                            $module
                        ),

                        'description' => 'Allows the user to ' .
                            Str::lower(
                                $this->displayName(
                                    $module,
                                    $action
                                )
                            ) . '.',
                    ]
                );

                $permissions->put(
                    $name,
                    $permission
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN
        |--------------------------------------------------------------------------
        */

        $superAdmin = Role::updateOrCreate(
            [
                'name' => 'super-admin',
            ],
            [
                'display_name' => 'Super Admin',
                'description' => 'Full access to every part of the administration system.',
                'is_system' => true,
            ]
        );

        /*
        | Super Admin always receives every current permission.
        */

        $superAdmin->permissions()->sync(
            $permissions->pluck('id')->all()
        );

        /*
        |--------------------------------------------------------------------------
        | ADMIN
        |--------------------------------------------------------------------------
        */

        $admin = Role::updateOrCreate(
            [
                'name' => 'admin',
            ],
            [
                'display_name' => 'Admin',
                'description' => 'Full operational access to marketplace management.',
                'is_system' => true,
            ]
        );

        $this->addDefaultPermissions(
            $admin,
            $this->permissionIds(
                $permissions,
                [
                    'dashboard',
                    'books',
                    'book_conditions',
                    'book_requests',
                    'categories',
                    'authors',
                    'publishers',
                    'refunds',
                    'shipping',
                    'coupons',
                    'orders',
                    'payments',
                    'users',
                    'sellers',
                    'seller_applications',
                    'reviews',
                    'messages',
                    'banners',
                    'blogs',
                    'faq',
                    'reports',
                    'analytics',
                ]
            )
        );

        /*
        |--------------------------------------------------------------------------
        | MANAGER
        |--------------------------------------------------------------------------
        */

        $manager = Role::updateOrCreate(
            [
                'name' => 'manager',
            ],
            [
                'display_name' => 'Manager',
                'description' => 'Manages marketplace inventory, sales and daily operations.',
                'is_system' => true,
            ]
        );

        $this->addDefaultPermissions(
            $manager,
            $this->permissionIds(
                $permissions,
                [
                    'dashboard',
                    'books',
                    'book_conditions',
                    'book_requests',
                    'refunds',
                    'shipping',
                    'coupons',
                    'orders',
                    'payments',
                    'users',
                    'sellers',
                    'seller_applications',
                    'reports',
                    'analytics',
                ]
            )
        );

        /*
        |--------------------------------------------------------------------------
        | EDITOR
        |--------------------------------------------------------------------------
        */

        $editor = Role::updateOrCreate(
            [
                'name' => 'editor',
            ],
            [
                'display_name' => 'Editor',
                'description' => 'Manages books and editorial content.',
                'is_system' => true,
            ]
        );

        $this->addDefaultPermissions(
            $editor,
            $this->permissionIds(
                $permissions,
                [
                    'dashboard',
                    'books',
                    'book_conditions',
                    'categories',
                    'authors',
                    'publishers',
                    'reviews',
                    'banners',
                    'blogs',
                    'faq',
                ]
            )
        );

        /*
        |--------------------------------------------------------------------------
        | MODERATOR
        |--------------------------------------------------------------------------
        */

        $moderator = Role::updateOrCreate(
            [
                'name' => 'moderator',
            ],
            [
                'display_name' => 'Moderator',
                'description' => 'Moderates books, reviews and marketplace content.',
                'is_system' => true,
            ]
        );

        /*
        | Moderator gets normal access to content moderation modules.
        */

        $this->addDefaultPermissions(
            $moderator,
            $this->permissionIds(
                $permissions,
                [
                    'dashboard',
                    'reviews',
                    'banners',
                    'blogs',
                    'faq',
                ]
            )
        );

        /*
        | Books are restricted to moderation actions only.
        */

        $this->addDefaultPermissions(
            $moderator,
            $this->permissionIds(
                $permissions,
                [
                    'books',
                ],
                [
                    'view',
                    'approve',
                    'reject',
                ]
            )
        );

        /*
        |--------------------------------------------------------------------------
        | SUPPORT
        |--------------------------------------------------------------------------
        */

        $support = Role::updateOrCreate(
            [
                'name' => 'support',
            ],
            [
                'display_name' => 'Support',
                'description' => 'Handles customer, seller and order support.',
                'is_system' => true,
            ]
        );

        $this->addDefaultPermissions(
            $support,
            $this->permissionIds(
                $permissions,
                [
                    'dashboard',
                    'users',
                    'sellers',
                    'seller_applications',
                    'orders',
                    'reviews',
                    'messages',
                ]
            )
        );

        /*
        |--------------------------------------------------------------------------
        | ACCOUNTANT
        |--------------------------------------------------------------------------
        */

        $accountant = Role::updateOrCreate(
            [
                'name' => 'accountant',
            ],
            [
                'display_name' => 'Accountant',
                'description' => 'Manages payments, refunds and financial reports.',
                'is_system' => true,
            ]
        );

        $this->addDefaultPermissions(
            $accountant,
            $this->permissionIds(
                $permissions,
                [
                    'dashboard',
                    'orders',
                    'payments',
                    'refunds',
                    'coupons',
                    'reports',
                    'analytics',
                ]
            )
        );

        /*
        |--------------------------------------------------------------------------
        | SELLER
        |--------------------------------------------------------------------------
        */

        $seller = Role::updateOrCreate(
            [
                'name' => 'seller',
            ],
            [
                'display_name' => 'Seller',
                'description' => 'Marketplace seller access.',
                'is_system' => true,
            ]
        );

        $this->addDefaultPermissions(
            $seller,
            $this->permissionIds(
                $permissions,
                [
                    'books',
                ],
                [
                    'view',
                    'create',
                    'edit',
                    'delete',
                ]
            )
        );

        /*
        |--------------------------------------------------------------------------
        | MEMBER
        |--------------------------------------------------------------------------
        */

        $member = Role::updateOrCreate(
            [
                'name' => 'user',
            ],
            [
                'display_name' => 'Member',
                'description' => 'Standard marketplace account.',
                'is_system' => true,
            ]
        );

        /*
        | Member has no admin permissions.
        |
        | We intentionally do not call sync() here so that
        | future custom permissions can be managed from the panel.
        */

        /*
        |--------------------------------------------------------------------------
        | SYNCHRONIZE EXISTING BUSINESS ROLES
        |--------------------------------------------------------------------------
        |
        | users.role remains responsible for the application's
        | existing business logic.
        |
        | role_user provides granular RBAC permissions.
        |
        */

        User::where('role', 'admin')
            ->get()
            ->each(function (User $user) use ($admin) {
                $user->roles()->syncWithoutDetaching([
                    $admin->id,
                ]);
            });

        User::where('role', 'seller')
            ->get()
            ->each(function (User $user) use ($seller) {
                $user->roles()->syncWithoutDetaching([
                    $seller->id,
                ]);
            });

        User::where('role', 'user')
            ->get()
            ->each(function (User $user) use ($member) {
                $user->roles()->syncWithoutDetaching([
                    $member->id,
                ]);
            });

        /*
        |--------------------------------------------------------------------------
        | YOUR SUPER ADMIN ACCOUNT
        |--------------------------------------------------------------------------
        |
        | Your existing users.role remains "admin".
        | The RBAC role gives your account full Super Admin access.
        |
        */

        $yourAccount = User::where('email', 'admin@gmail.com')->first();

        if ($yourAccount) {
            $yourAccount->update([
                'role' => 'admin',
            ]);

            $yourAccount->roles()->sync([
                $superAdmin->id,
            ]);

            $this->command->info(
                'Super Admin assigned to: ' . $yourAccount->email
            );
        } else {
            $this->command->warn(
                'User "admin@gmail.com" was not found. Super Admin was not assigned.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DONE
        |--------------------------------------------------------------------------
        */

        $this->command->info(
            'Roles and permissions seeded successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Add Default Permissions
    |--------------------------------------------------------------------------
    |
    | syncWithoutDetaching() is intentional.
    |
    | This means:
    | - default permissions are added
    | - manually assigned permissions are NOT removed
    |
    */

    private function addDefaultPermissions(
        Role $role,
        array $permissionIds
    ): void {
        $role->permissions()->syncWithoutDetaching(
            $permissionIds
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Permission IDs
    |--------------------------------------------------------------------------
    */

    private function permissionIds(
        $permissions,
        array $modules,
        ?array $actions = null
    ): array {
        return $permissions
            ->filter(
                function (Permission $permission) use (
                    $modules,
                    $actions
                ) {
                    [$module, $action] = explode(
                        '.',
                        $permission->name,
                        2
                    );

                    return in_array(
                        $module,
                        $modules,
                        true
                    )
                        && (
                            $actions === null
                            || in_array(
                                $action,
                                $actions,
                                true
                            )
                        );
                }
            )
            ->pluck('id')
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Permission Group
    |--------------------------------------------------------------------------
    */

    private function groupName(string $module): string
    {
        return match ($module) {
            'book_conditions',
            'book_requests' => 'Books',

            'email_settings',
            'activity_logs' => 'System',

            default => Str::headline($module),
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Permission Display Name
    |--------------------------------------------------------------------------
    */

    private function displayName(
        string $module,
        string $action
    ): string {
        return Str::headline($action)
            . ' '
            . Str::headline($module);
    }
}
