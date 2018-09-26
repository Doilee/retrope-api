<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property Session session
 * @property User user
 */
class Invite extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'token'
    ];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
