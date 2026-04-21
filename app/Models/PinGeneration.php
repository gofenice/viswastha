<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class PinGeneration extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pin_generations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'package_id',
        'unique_id',        // Added unique_id
        'password',         // Added password
        'no_pin',           // Make sure to include this if you need it
        'pin_amount',       // Renamed to pin_amount for consistency
        'status',
        'used',             // Added used
        'transfer_to',      // Added transfer_to
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'pin_amount' => 'decimal:2',  // Make sure this matches your DB schema for decimal fields
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the pin generation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the package associated with the pin generation.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
    public function package_name(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
    public function user_name(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function transfre_user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'transfer_to');
    }
    public function userPackages()
    {
        return $this->hasOne(UserPackage::class, 'pin_id');
    }
}
