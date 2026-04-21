<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalBodyType extends Model
{
    use HasFactory;

    protected $table = 'local_body_types';

    protected $fillable = [
        'country_id',
        'state_id',
        'district_id',
        'name',
        'lbt_code',
    ];

    // Relationships

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'state_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'district_id');
    }
}
