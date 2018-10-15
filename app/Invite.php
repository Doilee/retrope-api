<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property Player player
 */
class Invite extends Model
{
    protected $fillable = [
        'player_id',
        'token'
    ];

    const UPDATED_AT = null;

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
