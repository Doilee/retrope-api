<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dislike extends Model
{
    protected $fillable = [
        'player_id',
        'action_id',
    ];

    const UPDATED_AT = null;

    public function retrospective()
    {
        return $this->belongsTo(Action::class);
    }
}
