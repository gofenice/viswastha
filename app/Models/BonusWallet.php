<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusWallet extends Model
{
    use HasFactory;

    protected $table = 'bonus_wallet';

    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'is_redeemed',
        'status',
    ];

    /**
     * Relationship: BonusWallet belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
