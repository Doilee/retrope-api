<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $fillable = [
        'player_id',
        'retrospective_id',
    ];

    const UPDATED_AT = null;

    public function retrospective()
    {
        return $this->belongsTo(Retrospective::class);
    }
}
