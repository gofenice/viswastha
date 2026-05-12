<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminWalletBalance extends Model
{
    protected $table = 'admin_wallet_balances';

    protected $fillable = ['wallet_type', 'balance'];

    public static function getBalance(string $type): float
    {
        return (float) (static::where('wallet_type', $type)->value('balance') ?? 0);
    }

    public static function addBalance(string $type, float $amount): void
    {
        static::updateOrCreate(
            ['wallet_type' => $type],
            ['balance' => 0]
        );
        static::where('wallet_type', $type)->increment('balance', $amount);
    }

    public static function setBalance(string $type, float $amount): void
    {
        static::updateOrCreate(
            ['wallet_type' => $type],
            ['balance' => $amount]
        );
        static::where('wallet_type', $type)->update(['balance' => $amount]);
    }
}
