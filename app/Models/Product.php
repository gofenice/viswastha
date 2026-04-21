<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'product_name',
        'product_code',
        'package_id',
        'product_image',
        'product_description',
        'product_control',
        'product_status',
    ];

    protected $casts = [
        'product_status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Define the relationship with the Package model.
     */
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
