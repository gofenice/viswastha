<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PairMatch extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pair_matchs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id', 'pair_user1_id', 'pair_user2_id', 'package_id',
        'pair_match_income', 'sponsor_id', 'pair_match_income_date', 'status'
    ];

    /**
     * Get the parent user for this pair match.
     */
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Get the first user in the pair.
     */
    public function pairUser1()
    {
        return $this->belongsTo(User::class, 'pair_user1_id');
    }

    /**
     * Get the second user in the pair.
     */
    public function pairUser2()
    {
        return $this->belongsTo(User::class, 'pair_user2_id');
    }

    /**
     * Get the package associated with the pair match.
     */
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    /**
     * Get the pair_user_1 that owns the PairMatch
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pair_user_1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pair_user1_id');
    }

    /**
     * Get the pair_user_2 that owns the PairMatch
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pair_user_2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pair_user2_id');
    }

    /**
     * Get the pair_match_income associated with the PairMatch
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function pair_income(): HasOne
    {
        return $this->hasOne(PairMatchIncome::class, 'pair_match_id');
    }
    /**
     * Get the sponsor_name associated with the PairMatch
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function sponsor_name(): belongsTo
    {
        return $this->belongsTo(User::class, 'sponsor_id');
    }
}
