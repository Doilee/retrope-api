<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Invite
 *
 * @property Player player
 * @property int $id
 * @property int $session_id
 * @property int $email
 * @property string $token
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \App\Player $player
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invite whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invite whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invite whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invite whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invite whereToken($value)
 * @mixin \Eloquent
 * @property int $player_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Invite wherePlayerId($value)
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
