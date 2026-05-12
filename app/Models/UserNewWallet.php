<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserNewWallet extends Model
{
    protected $table = 'user_new_wallets';

    protected $fillable = ['user_id', 'balance', 'total_earned'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function forUser(int $userId): self
    {
        return self::firstOrCreate(
            ['user_id' => $userId],
            ['balance' => 0, 'total_earned' => 0]
        );
    }

    public function credit(float $amount): void
    {
        $this->increment('balance', $amount);
        $this->increment('total_earned', $amount);
    }
}
