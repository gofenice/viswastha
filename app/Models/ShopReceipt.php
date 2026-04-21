<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shop_id',
        'acc_holder_name',
        'amount',
        'date_of_send',
        'transaction_id',
        'image',
        'remarks',
        'status',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    // Status Accessor (for easy label use in Blade)
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            0 => 'Pending',
            1 => 'Approved',
            2 => 'Rejected',
            default => 'Unknown',
        };
    }

    // Status Badge Color (optional)
    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            0 => 'badge-warning',
            1 => 'badge-success',
            2 => 'badge-danger',
            default => 'badge-secondary',
        };
    }
}
