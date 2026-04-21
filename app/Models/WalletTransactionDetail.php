<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransactionDetail extends Model
{
    use HasFactory;

    protected $table = 'wallet_transaction_details';

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the user that owns the wallet transaction.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
