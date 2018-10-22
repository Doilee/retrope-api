<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Action
 *
 * @property Player|null player
 * @property-read \App\Player $player
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Vote[] $votes
 * @mixin \Eloquent
 * @property int $id
 * @property int $player_id
 * @property string $feedback
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action whereFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Action whereUpdatedAt($value)
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
