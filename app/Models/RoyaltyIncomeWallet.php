<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoyaltyIncomeWallet extends Model
{
    use HasFactory;

    protected $table = 'royalty_income_wallets';

    protected $fillable = [
        'user_id',
        'package_id',
        'amount',
        'is_redeemed',
        'status',
    ];

    protected $casts = [
        'is_redeemed' => 'boolean',
        'status' => 'integer',
    ];

    /**
     * Get the user associated with this royalty income wallet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the package associated with this royalty income wallet.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
