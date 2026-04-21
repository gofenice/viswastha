<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransactionDetail extends Model
{
    use HasFactory;

    protected $table = 'bank_transaction_details';

    protected $fillable = [
        'user_id',
        'acc_holder_name',
        'amount',
        'date_of_send',
        'transaction_id',
        'image',
        'status',
    ];

    protected $dates = ['date_of_send'];

    protected $casts = [
        'amount' => 'decimal:2',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
