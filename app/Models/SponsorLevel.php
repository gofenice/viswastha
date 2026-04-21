<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SponsorLevel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sponsor_levels';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'sponsor_id',
        'package_id',
        'package_category',
        'sponsor_level',
        'amount',
        'is_redeemed',
        'status',
    ];

    /**
     * Get the user associated with the sponsor level.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the sponsor associated with the sponsor level.
     */
    public function sponsor()
    {
        return $this->belongsTo(User::class, 'sponsor_id');
    }

    /**
     * Get the package associated with the sponsor level.
     */
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
