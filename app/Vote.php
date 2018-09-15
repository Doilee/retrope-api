<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = [
        'player_id',
        'retrospective_id',
        'value',
    ];

    const UPDATED_AT = null;

    public function retrospective()
    {
        return $this->belongsTo(Retrospective::class);
    }

    public function isLike()
    {
        return $this->value === 1;
    }

    public function isDislike()
    {
        return $this->value === -1;
    }
}
