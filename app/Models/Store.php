<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'name',
        'slug',
        'description',
        'logo',
        'phone',
        'address',
        'status',
        'accept_orders',
        'auto_approve_orders',
        'processing_time',
        'minimum_order_amount',
        'order_note',
    ];

    protected $casts = [
        'accept_orders' => 'boolean',
        'auto_approve_orders' => 'boolean',
        'processing_time' => 'integer',
        'minimum_order_amount' => 'decimal:2',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Store Books
    |--------------------------------------------------------------------------
    */

    public function books()
    {
        return $this->hasMany(Book::class, 'seller_id', 'seller_id');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

}