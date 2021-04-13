<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function confirm()
    {
        $this->is_confirmed = 1;
        $this->save();
    }

    public function expire()
    {
        $this->is_expired = 1;
        $this->save();
    }

    public function isConfirmed()
    {
        return $this->is_confirmed;
    }

    public function isExpired()
    {
        $currentMoment = Carbon::now();
        $createdAtDate = Carbon::parse($this->created_at);
        $difference = $createdAtDate->diffInSeconds($currentMoment);
        return $difference > 600;  // Expiration period = 10 minutes = 600 seconds
    }
}
