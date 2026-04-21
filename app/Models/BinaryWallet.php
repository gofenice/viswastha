<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BinaryWallet extends Model
{
    protected $fillable = [
        'user_id', 'balance', 'total_earned', 'total_withdrawn',
        'carry_forward_left', 'carry_forward_right',
    ];

    protected $casts = [
        'balance'           => 'decimal:2',
        'total_earned'      => 'decimal:2',
        'total_withdrawn'   => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Get or create wallet for a user. */
    public static function forUser(int $userId): self
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            ['balance' => 0, 'total_earned' => 0, 'total_withdrawn' => 0,
             'carry_forward_left' => 0, 'carry_forward_right' => 0]
        );
    }

    /** Credit an amount and update totals atomically. */
    public function credit(float $amount): void
    {
        $this->increment('balance', $amount);
        $this->increment('total_earned', $amount);
    }

    /** Debit an amount (withdrawal). */
    public function debit(float $amount): void
    {
        $this->decrement('balance', $amount);
        $this->increment('total_withdrawn', $amount);
    }
}
