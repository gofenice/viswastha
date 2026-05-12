<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminWalletLedger extends Model
{
    protected $table = 'admin_wallet_ledger';

    protected $fillable = ['wallet_type', 'amount', 'distribution_id'];

    public function distribution()
    {
        return $this->belongsTo(WalletDistribution::class, 'distribution_id');
    }
}
