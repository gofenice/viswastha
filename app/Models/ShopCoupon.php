<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCoupon extends Model
{
    use HasFactory;

    protected $table = 'shop_coupons';

    protected $fillable = [
        'shop_id',
        'coupon_code',
        'amount',
        'balance',
        'recharge_count',
        'status',
        'last_recharged_at',
    ];

    /**
     * Relationship with Shop
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
}
