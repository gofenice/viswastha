<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExecutiveUser extends Model
{
    use HasFactory;

    protected $table = 'executive_users';

    protected $fillable = [
        'user_id',
        'status',
    ];

    /**
     * Relation to User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
