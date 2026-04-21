<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrivilegeUserWallet extends Model
{
    use HasFactory;

    protected $table = 'privilege_user_wallets';

    protected $fillable = [
        'user_id',
        'amount',
        'status',
    ];

    /**
     * Relationship: belongs to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
