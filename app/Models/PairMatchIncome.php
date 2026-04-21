<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PairMatchIncome extends Model
{
    use HasFactory;

    protected $table = 'pair_match_incomes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pair_match_id',
        'user_id',
        'income',
        'package_id',
        'status',
    ];

    /**
     * Get the associated pair match.
     */
    public function pairMatch()
    {
        return $this->belongsTo(PairMatch::class, 'pair_match_id');
    }

    /**
     * Get the associated user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the associated package.
     */
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    /**
     * Get the pair_match that owns the PairMatchIncome
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pair_match(): BelongsTo
    {
        return $this->belongsTo(PairMatch::class, 'pair_match_id');
    }
}
