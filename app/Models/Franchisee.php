<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Franchisee extends Model
{
    use HasFactory;

    protected $fillable = [
        'lbt_id',
        'lb_id',
        'user_id',
        'status',
    ];

    // Relationships
       public function localBodyType()
    {
        return $this->belongsTo(LocalBodyType::class, 'lbt_id');
    }
    
    public function localBody()
    {
        return $this->belongsTo(LocalBody::class, 'lb_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
