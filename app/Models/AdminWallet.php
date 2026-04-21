<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminWallet extends Model
{
    use HasFactory;

    protected $table = 'admin_wallet'; // Table name

    protected $fillable = [
        'admin_id',
        'from_user_id',
        'amount',
        'type',
        'status',
    ];

    /**
     * Get the admin associated with the wallet transaction.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the user who initiated the transaction.
     */
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }
}
