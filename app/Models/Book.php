<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;

class Book extends Model
{
    protected $fillable = [
        'title',
        'isbn',
        'category_id',
        'author_id',
        'publisher_id',
        'seller_id',
        'description',
        'cover',
        'publication_year',
        'pages',
        'language',
        'price',
        'discount_type',
        'discount_value',
        'discount_start_at',
        'discount_end_at',
        'stock',
        'condition',
        'status',
    ];


    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'price' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'discount_start_at' => 'datetime',
        'discount_end_at' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    public function author()
    {
        return $this->belongsTo(Author::class);
    }


    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }


    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }


    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


    public function orders()
    {
        return $this->hasMany(Order::class);
    }


    /*
    |--------------------------------------------------------------------------
    | Discount
    |--------------------------------------------------------------------------
    */

    public function getDiscountedPriceAttribute()
    {
        $price = (float) $this->price;

        $discountValue = (float) (
            $this->discount_value ?? 0
        );

        $discountType = $this->discount_type ?? 'none';


        /*
        |--------------------------------------------------------------------------
        | No Discount
        |--------------------------------------------------------------------------
        */

        if ($discountType === 'none') {
            return $price;
        }


        /*
        |--------------------------------------------------------------------------
        | Percentage Discount
        |--------------------------------------------------------------------------
        */

        if ($discountType === 'percentage') {

            $discountAmount =
                $price * ($discountValue / 100);

            return max(
                0,
                $price - $discountAmount
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Fixed Discount
        |--------------------------------------------------------------------------
        */

        if ($discountType === 'fixed') {

            return max(
                0,
                $price - $discountValue
            );
        }


        return $price;
    }


    /*
    |--------------------------------------------------------------------------
    | Active Discount
    |--------------------------------------------------------------------------
    */

    public function getIsDiscountActiveAttribute()
    {
        $now = now();


        /*
        |--------------------------------------------------------------------------
        | Basic Discount Check
        |--------------------------------------------------------------------------
        */

        if (
            ($this->discount_type ?? 'none') === 'none' ||
            (float) ($this->discount_value ?? 0) <= 0
        ) {
            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | Start Date
        |--------------------------------------------------------------------------
        */

        if (
            $this->discount_start_at &&
            $this->discount_start_at->greaterThan($now)
        ) {
            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | End Date
        |--------------------------------------------------------------------------
        */

        if (
            $this->discount_end_at &&
            $this->discount_end_at->lessThan($now)
        ) {
            return false;
        }


        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | Discount Label
    |--------------------------------------------------------------------------
    */

    public function getDiscountLabelAttribute()
    {
        if (!$this->is_discount_active) {
            return null;
        }


        if ($this->discount_type === 'percentage') {

            return rtrim(
                rtrim(
                    number_format(
                        (float) $this->discount_value,
                        2,
                        '.',
                        ''
                    ),
                    '0'
                ),
                '.'
            ) . '% OFF';
        }


        if ($this->discount_type === 'fixed') {

            return '$' .
                number_format(
                    (float) $this->discount_value,
                    2
                ) .
                ' OFF';
        }


        return null;
    }
}
