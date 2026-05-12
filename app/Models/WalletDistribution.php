<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletDistribution extends Model
{
    protected $table = 'wallet_distributions';

    protected $fillable = [
        'wallet_type',
        'pool_amount',
        'user_count',
        'per_user_amount',
        'total_distributed',
        'remainder',
    ];

    public function userCredits()
    {
        return $this->hasMany(UserWalletCredit::class, 'distribution_id');
    }
}
