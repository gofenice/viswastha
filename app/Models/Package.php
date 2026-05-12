<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    protected $table = 'packages';
    protected $fillable = [
        'name',
        'amount',
        'binary_commission',
        'sponsor_commission',
        'sponsor_eligible_package_ids',
        'auto_upgrade_count',
        'auto_upgrade_to_package_id',
        'daily_pair_cap',
        'package_code',
        'package_cat',
        'status',
        'color',
        'privilege_wallet_income',
        'board_wallet_income',
        'executive_wallet_income',
        'royalty_wallet_income',
    ];

    protected $casts = [
        'sponsor_eligible_package_ids' => 'array',
    ];

    
    public function users()
    {
        return $this->hasMany(User::class, 'package_id'); // Adjust 'package_id' as per your DB schema
    }
}
