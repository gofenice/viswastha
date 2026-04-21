<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasicRank extends Model
{
    use HasFactory;

    protected $table = 'basic_ranks';

    protected $fillable = [
        'name',
        'level',
        'status',
    ];
}
