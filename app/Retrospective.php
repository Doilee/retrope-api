<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @property int $id
 * @property \Carbon\Carbon|null $voting_starts_at
 * @property \Carbon\Carbon|null $expires_at
 * @property boolean is_public
 * Class Session
 * @package App
 */
class Retrospective extends Model
{
    protected $fillable = [
        'name',
        'is_public',
        'scheduled_at',
        'starts_at',
        'voting_starts_at',
        'expires_at',
    ];

    protected $dates = [
        'scheduled_at',
        'starts_at',
        'voting_starts_at',
        'expires_at',
    ];

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
        $this->voting_starts_at = now()->addSeconds($timer ?? 0)->toDateTimeString();
        $this->expires_at = now()->addSeconds($timer * 2 ?? 0)->toDateTimeString();

        return $this->save();
    }
}
