<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBankingDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ifs_code',
        'bank_name',
        'branch_name',
        'account_number',
        'account_holder_name',
        'bank_passbook_image',
        'pancard_image',
        'status',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
