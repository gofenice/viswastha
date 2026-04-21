<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $table = 'order_products';

    protected $fillable = [
        'online_order_id',
        'user_id',
        'name',
        'quantity',
        'unit_price_tax_excl',
        'total_price_tax_excl',
        'tax_amount',
        'franchise_code',
        'is_approve',
        'status',
    ];

    // Relationship: product belongs to an online order
    public function onlineOrder()
    {
        return $this->belongsTo(OnlineOrder::class, 'online_order_id');
    }

    // Relationship: product belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship: franchisee (also user table)
    public function franchisee()
    {
        return $this->belongsTo(User::class, 'franchise_code');
    }
}
