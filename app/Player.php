<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
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
        return $this->hasMany(Like::class);
    }

    public function dislikes()
    {
        return $this->hasMany(Dislike::class);
    }

    public function like(Retrospective $retrospective)
    {
        $like = $this->likes()->where('retrospective_id', $retrospective->id)->first();

        if ($like) {
            $like->delete();

            return false;
        }

        $this->likes()->create([
            'retrospective_id' => $retrospective->id
        ]);

        return true;
    }

    public function dislike(Retrospective $retrospective)
    {
        $dislike = $this->dislikes()->where('retrospective_id', $retrospective->id)->first();

        if ($dislike) {
            $dislike->delete();

            return false;
        }

        $this->dislikes()->create([
            'retrospective_id' => $retrospective->id
        ]);

        return true;
    }
}
