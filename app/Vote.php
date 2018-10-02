<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = [
        'player_id',
        'action_id',
        'value',
    ];

    const UPDATED_AT = null;

    public function retrospective()
    {
        return $this->belongsTo(Action::class);
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
