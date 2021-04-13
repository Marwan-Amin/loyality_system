<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'points',
        'is_expired',
        'is_confirmed',
    ];

    protected $casts = [
        'is_expired' => 'boolean',
        'is_confirmed' => 'boolean',
    ];
}
