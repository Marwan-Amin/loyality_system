<?php

namespace App\Models;

use App\Helpers\Constants;
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
        'status',
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
        $this->status = Constants::STATUS_CONFIRMED;
        $this->save();
    }

    public function expire()
    {
        $this->status = Constants::STATUS_EXPIRED;
        $this->save();
    }

    public function isConfirmed()
    {
        if ($this->status == Constants::STATUS_CONFIRMED) {
            return true;
        }
        return false;
    }

    public function isExpired()
    {
        $currentMoment = Carbon::now();
        $createdAtDate = Carbon::parse($this->created_at);
        $difference = $createdAtDate->diffInSeconds($currentMoment); // Expiration period = 10 minutes = 600 seconds
        if ($difference > 600 || $this->status == Constants::STATUS_EXPIRED) {
            return true;
        }
        return false;
    }
}
