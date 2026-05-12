<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseWalletEntry extends Model
{
    protected $table = 'purchase_wallet_entries';

    protected $fillable = [
        'user_id',
        'package_id',
        'wallet_type',
        'amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
