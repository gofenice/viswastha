<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryPercentage extends Model
{
    // Table name (optional if it follows Laravel's naming convention)
    protected $table = 'category_percentages';

    // Mass assignable attributes
    protected $fillable = [
        'name',
        'percentage',
        'status',
    ];

    // Casts
    protected $casts = [
        'percentage' => 'float',
        'status' => 'integer',
    ];
}
