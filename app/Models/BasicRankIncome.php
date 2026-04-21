<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasicRankIncome extends Model
{
    use HasFactory;

    protected $table = 'basic_rank_incomes';

    protected $fillable = [
        'rank_id',
        'amount',
        'user_id',
        'package_id',
        'is_redeemed',
        'status',
    ];

    /**
     * Relationships
     */

    // Relation to BasicRank
    public function rank()
    {
        return $this->belongsTo(BasicRank::class, 'rank_id');
    }

    // Relation to User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relation to Package
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
