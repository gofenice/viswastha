<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildMotherPayment extends Model
{
    use HasFactory;

    protected $table = 'child_mother_payments';

    protected $fillable = [
        'child_id',
        'mother_id',
        'amount',
        'type',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => 'integer',
    ];

    public function child()
    {
        return $this->belongsTo(User::class, 'child_id');
    }

    public function mother()
    {
        return $this->belongsTo(User::class, 'mother_id');
    }
}
