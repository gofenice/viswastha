<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrashMoney extends Model
{
    use HasFactory;

    // Optional: specify the table name if different from plural
    protected $table = 'trash_money';

    // Optional: specify fillable fields for mass assignment
    protected $fillable = [
        'user_id',
        'amount',
        'reason',
        'trashed_by',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function trashedBy()
    {
        return $this->belongsTo(User::class, 'trashed_by');
    }
}
