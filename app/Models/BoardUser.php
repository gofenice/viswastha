<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoardUser extends Model
{
    use HasFactory;

    // Table name (if different from plural of model)
    protected $table = 'board_users';

    // Mass assignable fields
    protected $fillable = [
        'user_id',
        'status',
    ];

    /**
     * Relationship: BoardUser belongs to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
