<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinTransferDetail extends Model
{
    use HasFactory;

    protected $table = 'pin_transfer_details';

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'pin_id',
        'used',
        'status',
    ];

    public $timestamps = true;

    /**
     * Relationship with User (From User)
     */
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Relationship with User (To User)
     */
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    /**
     * Relationship with PinGeneration
     */
    public function pindetail()
    {
        return $this->belongsTo(PinGeneration::class, 'pin_id');
    }

    public function userPackages()
    {
        return $this->hasMany(UserPackage::class, 'pin_id', 'pin_id');
    }

    public function pin()
{
    return $this->belongsTo(PinGeneration::class, 'pin_id');
}
}
