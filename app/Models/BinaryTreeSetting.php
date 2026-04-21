<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BinaryTreeSetting extends Model
{
    use HasFactory;

    protected $fillable = ['root_user_id', 'migration_complete'];

    public function rootUser()
    {
        return $this->belongsTo(User::class, 'root_user_id');
    }

    public static function current(): self
    {
        return self::firstOrCreate([], ['root_user_id' => null, 'migration_complete' => false]);
    }
}
