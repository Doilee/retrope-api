<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PasswordReset
 *
 * @property int $id
 * @property string $email
 * @property string $token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PasswordReset whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PasswordReset whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PasswordReset whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PasswordReset whereToken($value)
 * @mixin \Eloquent
 */
class PasswordReset extends Model
{
    protected $fillable = [
        'email', 'token'
    ];

    const UPDATED_AT = null;
}
