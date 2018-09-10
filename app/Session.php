<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $fillable = [
        'name',
        'timer',
        'is_public',
        'invitation_code',
        'started_at',
        'finished_at'
    ];

    protected $dates = ['started_at', 'finished_at'];

    public function host()
    {
        return $this->belongsTo(User::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
