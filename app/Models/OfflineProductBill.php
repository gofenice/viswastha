<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OfflineProductBill extends Model
{
    use HasFactory;

    protected $table = 'offline_product_bills';

    protected $fillable = [
        'user_id',
        'shop_id',
        'lbt_id',
        'lb_id',
        'category_id',
        'purchase_date',
        'product_count',
        'total',
        'image_path',
        'status',
        'admin_status',
    ];

    public $timestamps = true;

    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function category()
    {
        return $this->belongsTo(CategoryPercentage::class, 'category_id');
    }
    public function localBodyType()
    {
        return $this->belongsTo(LocalBodyType::class, 'lbt_id');
    }

    public function localBody()
    {
        return $this->belongsTo(LocalBody::class, 'lb_id');
    }
}
