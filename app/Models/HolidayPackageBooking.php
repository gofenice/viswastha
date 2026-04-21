<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayPackageBooking extends Model
{
    use HasFactory;

    // Table name (optional because Laravel will auto-detect
    // based on model name, but you can keep it explicit)
    protected $table = 'holiday_package_bookings';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'package_id',
        'address',
        'phone_no',
        'email',
        'date',
        'invoice_path',
        'status',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
