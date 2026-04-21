<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExecutiveUserWallet extends Model
{
    use HasFactory;

    protected $table = 'executive_user_wallets';

    protected $fillable = [
        'user_id',
        'amount',
        'status',
    ];

    /**
     * Relation with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
