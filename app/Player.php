<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @property Session|null $session
 * Class Player
 * @package App
 */
class Player extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function retrospectives()
    {
        return $this->hasMany(Retrospective::class);
    }

    public function likes()
    {
        return $this->votes()->where('value', 1);
    }

    public function dislikes()
    {
        return $this->votes()->where('value', -1);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function like(Retrospective $retrospective) : bool
    {
        $vote = $this->vote($retrospective);

        if ($vote->isLike()) {
            $vote->delete();

            return false;
        }

        $vote->fill([
            'value' => 1
        ]);

        $vote->save();

        return true;
    }

    public function dislike(Retrospective $retrospective) : bool
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

    public function vote(Retrospective $retrospective)
    {
        $vote = $this->votes()->where('retrospective_id', $retrospective->id)->first();

        if (!$vote) {
            $vote = $this->votes()->make([
                'retrospective_id' => $retrospective->id,
            ]);
        }

        return $vote;
    }
}
