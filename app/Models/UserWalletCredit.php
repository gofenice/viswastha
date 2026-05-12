<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserWalletCredit extends Model
{
    protected $table = 'user_wallet_credits';

    protected $fillable = [
        'user_id',
        'distribution_id',
        'wallet_type',
        'amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function distribution()
    {
        return $this->belongsTo(WalletDistribution::class, 'distribution_id');
    }
}
