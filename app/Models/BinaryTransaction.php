<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BinaryTransaction extends Model
{
    protected $fillable = [
        'user_id', 'type', 'amount', 'description',
        'from_user_id', 'package_id', 'calc_date', 'meta',
    ];

    protected $casts = [
        'meta'      => 'array',
        'calc_date' => 'date',
        'amount'    => 'decimal:2',
    ];

    // Human-readable labels for each type
    const TYPE_LABELS = [
        'binary_pair'    => 'Binary Pair Income',
        'binary_sponsor' => 'Binary Sponsor Income',
        'prime_sponsor'  => 'Prime Sponsor Income',
        'withdrawal'     => 'Withdrawal',
        'admin_credit'   => 'Admin Credit',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /** Record a credit transaction and update the wallet in one call. */
    public static function credit(
        int     $userId,
        string  $type,
        float   $amount,
        string  $description = '',
        array   $extra = []
    ): self {
        $wallet = BinaryWallet::forUser($userId);
        $wallet->credit($amount);

        return self::create(array_merge([
            'user_id'     => $userId,
            'type'        => $type,
            'amount'      => $amount,
            'description' => $description,
        ], $extra));
    }
}
