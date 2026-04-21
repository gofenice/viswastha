<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyRankIncome extends Model
{
    use HasFactory;
    protected $table = 'company_rank_incomes';

    protected $primaryKey = 'id';

    // Specify the fields that can be mass-assigned.
    protected $fillable = [
        'rank_id',
        'amount',
        'user_id',
        'package_id',
        'is_redeemed',
        'status',
    ];

    protected $casts = [
        'is_redeemed' => 'boolean',
        'status' => 'integer',
        'amount' => 'decimal:2',
    ];

    // Define the relationships (if any)
    public function rank()
    {
        return $this->belongsTo(Rank::class, 'rank_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }
}
