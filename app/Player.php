<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @property Retrospective $retrospective
 * @property User user
 * Class Player
 * @package App
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

    public function invites()
    {
        return $this->hasMany(Invite::class);
    }

    public function likes()
    {
        return $this->votes()->where('value', 1);
    }

    public function dislikes()
    {
        return $this->votes()->where('value', -1);
    }

    public function like(Action $retrospective) : bool
    {
        $vote = $this->vote($retrospective);

        if ($vote->isLike()) {
            $vote->delete();

            return false;
        }

        $vote->save();

        return true;
    }

    public function dislike(Action $retrospective) : bool
    {
        $vote = $this->vote($retrospective);

        if ($vote->isDislike()) {
            $vote->delete();

            return false;
        }

        $vote->fill([
            'value' => -1
        ]);

        $vote->save();

        return true;
    }

    public function vote(Action $retrospective)
    {
        $vote = $this->votes()->where('action_id', $retrospective->id)->first();

        if (!$vote) {
            $vote = $this->votes()->make([
                'action_id' => $retrospective->id,
            ]);
        }

        return $vote;
    }
}
