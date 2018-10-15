<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property Player|null player
 */
class Action extends Model
{
    protected $fillable = [
        'feedback',
        'player_id',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function likes()
    {
        return $this->votes()->where('value', 1);
    }

    public function dislikes()
    {
        return $this->votes()->where('value', -1);
    }
}
