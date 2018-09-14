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

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function dislikes()
    {
        return $this->hasMany(Dislike::class);
    }
}
