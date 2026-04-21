<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivilegeUser extends Model
{
    use HasFactory;

    protected $table = 'privilege_users';

    protected $fillable = [
        'user_id',
        'status',
    ];

    /**
     * Relationship: PrivilegeUser belongs to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
