<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retrospective extends Model
{
    protected $fillable = [
        'feedback',
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
