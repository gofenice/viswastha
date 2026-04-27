<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BinaryPairLog extends Model
{
    protected $fillable = [
        'user_id', 'package_id', 'package_type', 'calc_date',
        'new_left', 'new_right', 'carry_in_left', 'carry_in_right',
        'total_left', 'total_right',
        'matched_pairs', 'capped_pairs', 'income',
        'carry_out_left', 'carry_out_right',
        'flushed_left', 'flushed_right',
        'prime_carry_out_left', 'prime_carry_out_right',
    ];

    public function package()
    {
        return $this->belongsTo(\App\Models\Package::class);
    }

    protected $casts = [
        'calc_date' => 'date',
        'income'    => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
