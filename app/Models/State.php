<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    // Specify the table name if it's not the plural form of the model
    protected $table = 'states';

    // Primary key
    protected $primaryKey = 'state_id';

    // Disable auto-incrementing if not using default 'id' column
    public $incrementing = true;

    // Primary key type
    protected $keyType = 'int';

    // Timestamps are handled manually in your migration
    public $timestamps = false;

    // Mass assignable attributes
    protected $fillable = [
        'state_name',
        'state_code',
        'total_district',
        'status',
        'created_at',
        'updated_at',
        'country_id',
    ];

    // If you want Laravel to handle timestamps automatically (optional)
    // protected $timestamps = true;
    // const CREATED_AT = 'created_at';
    // const UPDATED_AT = 'updated_at';

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
