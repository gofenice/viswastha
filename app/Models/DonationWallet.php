<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationWallet extends Model
{
    use HasFactory;

    protected $table = 'donation_wallets';

    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'status',
    ];

    /**
     * Get the user associated with the donation wallet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
