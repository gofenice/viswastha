<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BinaryPairLog extends Model
{
    protected $fillable = [
        'user_id', 'calc_date', 'package_type',
        'new_left', 'new_right', 'carry_in_left', 'carry_in_right',
        'total_left', 'total_right',
        'matched_pairs', 'capped_pairs', 'income',
        'carry_out_left', 'carry_out_right',
        'flushed_left', 'flushed_right',
    ];

    protected $casts = [
        'calc_date' => 'date',
        'income'    => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
