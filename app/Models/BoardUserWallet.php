<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardUserWallet extends Model
{
    use HasFactory;

    protected $table = 'board_user_wallets';

    protected $fillable = [
        'user_id',
        'amount',
        'status',
    ];

    /**
     * Relationship: belongs to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
