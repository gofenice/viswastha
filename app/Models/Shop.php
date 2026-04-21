<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shop extends Model
{
    use HasFactory;

    // If your table name is 'shops', this is not required (Laravel infers it).
    protected $table = 'shops';

    // Mass assignable attributes
    protected $fillable = [
        'name',
        'owner_id',
        'email',
        'phone',
        'address',
        'gst_number',
        'status',
        'state_id',
        'district_id',
        'lbt_id',
        'lb_id',
        'shop_profile',
    ];

    // If you're using timestamps and they are manually set to useCurrent()
    public $timestamps = true;

    // Define relationships (if any)
    public function offlineProductBills()
    {
        return $this->hasMany(OfflineProductBill::class);
    }
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function state()
    {
        return $this->belongsTo(State::class, 'state_id', 'state_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id', 'district_id');
    }
    public function localBodyType()
    {
        return $this->belongsTo(LocalBodyType::class, 'lbt_id');
    }

    public function localBody()
    {
        return $this->belongsTo(LocalBody::class, 'lb_id');
    }
}
