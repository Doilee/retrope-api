<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $fillable = [
        'name',
        'is_public',
        'invitation_code',
        'started_at',
        'completed_at',
        'expires_at',
    ];

    //todo: Change to datetime?
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

    public function isExpired()
    {
        return $this->expires_at ? $this->expires_at->isPast() : false;
    }

    public function start($timer = null)
    {
        $this->started_at = now();
        $this->expires_at = now()->addSeconds($timer ?? 0);

        return $this->save();
    }
}
