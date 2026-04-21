<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepurchaseWallet extends Model
{
    protected $table = 'repurchase_wallet';

    protected $fillable = [
        'user_id',
        'product_ordered_user_id',
        'amount',
        'order_id',
        'status',
        'is_redeemed',
        'amount_type',
        'sponsor_level',
        'percentage',
        'commission_amount',
        'commission_percentage',
        'shop_id',
    ];

    /**
     * Get the user who owns this repurchase wallet entry.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who ordered the product.
     */
    public function productOrderedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'product_ordered_user_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'id');
    }

    public function orderBill()
    {
        return $this->belongsTo(OfflineProductBill::class, 'order_id', 'id');
    }
}
