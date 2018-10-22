<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Vote
 *
 * @property Player|null player
 * @property Action|null action
 * @property int $id
 * @property int $player_id
 * @property int $retrospective_id
 * @property int $value
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \App\Action $action
 * @property-read \App\Player $player
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote whereRetrospectiveId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote whereValue($value)
 * @mixin \Eloquent
 * @property int $action_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vote whereActionId($value)
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
