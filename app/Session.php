<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $fillable = [
        'name',
        'timer',
        'is_public',
        'invitation_code',
        'started_at',
        'completed_at',
        'expires_at',
    ];

    protected $dates = ['started_at', 'completed_at', 'expires_at'];

    const UPDATED_AT = null;
    const CREATED_AT = null;

    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
