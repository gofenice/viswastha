<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPackage extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_packages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'package_id',
        'pin_id',
        'add_by',
        'status',
    ];

    /**
     * Get the user associated with this package.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the package associated with this record.
     */

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
    /**
     * Get the pin associated with this record.
     */
    public function pin()
    {
        return $this->belongsTo(PinGeneration::class, 'pin_id');
    }

    public function addedByUser()
    {
        return $this->belongsTo(User::class, 'add_by');
    }
}
