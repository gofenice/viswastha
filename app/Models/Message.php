<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'msg_from_id',
        'msg_to_id',
        'message',
        'msg_is_read',
        'msg_edited',
        'msg_status',
    ];
}
