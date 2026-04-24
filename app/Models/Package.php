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
        'daily_pair_cap',
        'package_code',
        'package_cat',
        'status',
    ];

    protected $casts = [
        'sponsor_eligible_package_ids' => 'array',
    ];

    
    public function users()
    {
        return $this->hasMany(User::class, 'package_id'); // Adjust 'package_id' as per your DB schema
    }
}
