<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExecutiveIncomeWallet extends Model
{
    use HasFactory;

    protected $table = 'executive_income_wallets';

    protected $fillable = [
        'user_id',
        'package_id',
        'amount',
        'is_redeemed',
        'status',
    ];

    /**
     * Relation with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation with Package
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
