<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $table = 'districts';

    protected $primaryKey = 'district_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'district_name',
        'district_code',
        'state_id',
        'country_id',
        'status',
        'created_at',
        'updated_at',
    ];

    // Define relationship to State
    public function state()
    {
        return $this
        ->belongsTo(State::class, 'state_id', 'state_id');
    }

       public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
