<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;

/**
 * Class User
 * @package App
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract, MustVerifyEmailContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, HasApiTokens, Notifiable, MustVerifyEmail;

    const GUEST_DRIVER = 'guest';
    const DEFAULT_DRIVER = 'default';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
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

    public function retrospective()
    {
        return $this->hasMany(Retrospective::class, 'host_id');
    }

    public function isGuest()
    {
        return $this->driver === self::GUEST_DRIVER;
    }
}
