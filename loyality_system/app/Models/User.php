<?php

namespace App\Models;

use App\Helpers\Constants;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'points',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function subtractPoints($points)
    {
        $this->points = $this->points - $points;
        $this->save();
    }

    public function transferPoints($points)
    {
        $this->points = $this->points + $points;
        $this->save();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'sender_id');
    }

    public function expiredTransactions()
    {
        return $this->hasMany(Transaction::class, 'sender_id')->where('status', Constants::STATUS_EXPIRED);
    }

    public function confirmedTransactions()
    {
        return $this->hasMany(Transaction::class, 'sender_id')->where('is_confirmed', Constants::STATUS_CONFIRMED);
    }
}
