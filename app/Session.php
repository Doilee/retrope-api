<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $fillable = [
        'name',
        'is_public',
        'starts_at',
        'expires_at',
        'completed_at',
        'scheduled_at'
    ];

    //todo: Change to datetime?
    protected $dates = ['starts_at', 'completed_at', 'expires_at', 'scheduled_at'];

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
        $this->starts_at = now()->toDateTimeString();
        $this->expires_at = now()->addSeconds($timer ?? 0)->toDateTimeString();

        return $this->save();
    }

    public function invites()
    {
        return $this->hasMany(Invite::class);
    }
}
