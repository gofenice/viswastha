<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasicRankAchieve extends Model
{
    use HasFactory;

    protected $table = 'basic_rank_achieves';

    protected $fillable = [
        'basic_rank_id',
        'user_id',
        'sponsor_id',
        'status',
        'rank_status',
    ];

    // Relationships
    public function rank()
    {
        return $this->belongsTo(BasicRank::class, 'basic_rank_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sponsor()
    {
        return $this->belongsTo(User::class, 'sponsor_id');
    }
}
