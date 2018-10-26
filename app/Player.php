<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Player
 *
 * @property Retrospective $retrospective
 * @property User user
 * Class Player
 * @package App
 * @property int $id
 * @property int $user_id
 * @property int $session_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Action[] $actions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Invite[] $invites
 * @property-read \App\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Vote[] $votes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Player whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Player whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Player whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Player whereSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Player whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Player whereUserId($value)
 * @mixin \Eloquent
 * @property int $retrospective_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Player whereRetrospectiveId($value)
 * @property string|null $joined_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Player whereJoinedAt($value)
 */
class Player extends Model
{
    protected $fillable = [
        'user_id',
        'retrospective_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function retrospective()
    {
        return $this->belongsTo(Retrospective::class);
    }

    public function actions()
    {
        return $this->hasMany(Action::class);
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

    public function vote(Action $action)
    {
        $vote = $this->votes()->make([
            'action_id' => $action->id,
        ]);

        return $vote;
    }
}
