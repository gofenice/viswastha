<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivilegeIncomeWallet extends Model
{
    use HasFactory;

    protected $table = 'privilege_income_wallets';

    protected $fillable = [
        'user_id',
        'package_id',
        'amount',
        'is_redeemed',
        'status',
    ];

    /**
     * Relationship: belongs to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: belongs to Package
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
