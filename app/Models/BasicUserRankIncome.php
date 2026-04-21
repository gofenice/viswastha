<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasicUserRankIncome extends Model
{
    use HasFactory;

    protected $table = 'basic_user_rank_incomes';

    protected $fillable = [
        'user_id',
        'rank_id',
        'amount',
        'status',
    ];

    /**
     * Relationship: Each record belongs to a User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Each record belongs to a BasicRank.
     */
    public function rank()
    {
        return $this->belongsTo(BasicRank::class, 'rank_id');
    }
}
