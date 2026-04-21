<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoyaltyIncomeUser extends Model
{
    use HasFactory;

    protected $table = 'royalty_income_users';

    protected $fillable = [
        'user_id',
        'status',
    ];

    /**
     * Relationship: Royalty income belongs to a user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
