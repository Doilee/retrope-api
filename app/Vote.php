<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property Player|null player
 * @property Action|null action
 */
class Vote extends Model
{
    const MAXIMUM_PER_PLAYER = 5;

    protected $fillable = [
        'player_id',
        'action_id',
        'value',
    ];

    const UPDATED_AT = null;

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function action()
    {
        return $this->belongsTo(Action::class);
    }

    public function isLike()
    {
        return $this->value === 1;
    }

    public function isDislike()
    {
        return $this->value === -1;
    }
}
