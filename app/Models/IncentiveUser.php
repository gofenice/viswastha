<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncentiveUser extends Model
{
    use HasFactory;

    protected $table = 'incentive_users';

    protected $fillable = [
        'user_id',
        'status',
    ];

    /**
     * Relationship: IncentiveUser belongs to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
