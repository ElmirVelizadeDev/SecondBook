<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\Shipping;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [

        'processing_deadline',

        // Order Information
        'order_number',

        // Relations
        'user_id',
        'book_id',

        // Price
        'book_price',
        'quantity',
        'total_price',
        'shipping_fee',

        // Payment
        'payment_method',
        'payment_status',

        // Order Status
        'order_status',
        'order_note',

        // Shipping
        'shipping_id',
        'full_name',
        'phone',
        'country',
        'city',
        'postal_code',
        'address',
        'delivery_estimate',

        // Extra
        'note',
        'archived_at',
    ];

    protected $casts = [
        'book_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'processing_deadline' => 'datetime',
        'archived_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }
    
    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }
}