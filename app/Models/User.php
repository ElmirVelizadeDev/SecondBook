<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Wishlist;
use App\Models\Notification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
        'role',
        'status',
        'phone',
        'profile_photo',
        'date_of_birth',
        'gender',
        'country',
        'city',
        'state',
        'postal_code',
        'address',
        'bio',
        'receive_email_notifications',
        'receive_order_updates',
        'receive_promotional_emails',
        'profile_visibility',
        'email_verified_at',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
        'last_login_at' => 'datetime',
        'receive_email_notifications' => 'boolean',
        'receive_order_updates' => 'boolean',
        'receive_promotional_emails' => 'boolean',
        'profile_visibility' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function isSeller(): bool
    {
        return $this->role === 'seller';
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function books()
    {
        return $this->hasMany(Book::class, 'seller_id');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()
            ->where('name', $role)
            ->exists();
    }

    public function hasPermission(string $permission): bool
    {
        /*
        | Super Admin Access
        |
        | Users with the "super-admin" role have access
        | to all permissions.
        */
        if ($this->hasRole('super-admin')) {
            return true;
        }

        return $this->roles()
            ->whereHas(
                'permissions',
                fn ($query) => $query->where('name', $permission)
            )
            ->exists();
    }

    public function hasAnyPermission(array $permissions): bool
    {
        return collect($permissions)
            ->contains(
                fn (string $permission) => $this->hasPermission($permission)
            );
    }

    public function settings(): HasOne
    {
        return $this->hasOne(UserSetting::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function sellerApplications()
    {
        return $this->hasMany(SellerApplication::class);
    }

    public function store(): HasOne
    {
        return $this->hasOne(Store::class, 'seller_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}

