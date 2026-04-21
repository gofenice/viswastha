<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRankHistory extends Model
{
    use HasFactory;

    protected $table = 'user_rank_histories';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'rank_id',
        'status',
    ];

    /**
     * Relationships
     */

    // A rank history belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // A rank history belongs to a rank
    public function rank()
    {
        return $this->belongsTo(Rank::class);
    }
}
