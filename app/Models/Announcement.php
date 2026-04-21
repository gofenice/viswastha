<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    // Allow mass assignment for these fields
    protected $fillable = [
        'image',
    ];
}
