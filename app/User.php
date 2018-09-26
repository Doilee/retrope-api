<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract, MustVerifyEmailContract
{
    use Authenticatable, Authorizable, HasApiTokens, Notifiable, MustVerifyEmail;

    const GUEST_DRIVER = 'admin';
    const DEFAULT_DRIVER = 'default';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nickname',
        'email',
        'password',
        'email_verified_at'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function session()
    {
        return $this->hasMany(Session::class, 'host_id');
    }

    public function isGuest()
    {
        return $this->driver === self::GUEST_DRIVER;
    }
}
