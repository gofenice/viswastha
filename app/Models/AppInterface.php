<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppInterface extends Model
{
    protected $table = 'app_interfaces';

    protected $fillable = [
        'name',
        'price',
        'image',
        'text',
        'status',
    ];

    // Optional: If you want to treat status as a boolean
    protected $casts = [
        'status' => 'boolean',
        'price' => 'decimal:2',
    ];
}
