<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineOrder extends Model
{
    use HasFactory;

    protected $table = 'online_orders';

    protected $fillable = [
        'order_id',
        'amount',
        'user_id',
        'category',
        'percentage',
        'franchisee_code',
        'is_approve',
        'status',
    ];

    // Relationship: order belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship: franchisee (also user table)
    public function franchisee()
    {
        return $this->belongsTo(User::class, 'franchisee_code');
    }

    // Relationship: order has many products
    public function products()
    {
        return $this->hasMany(OrderProduct::class, 'online_order_id');
    }
}
