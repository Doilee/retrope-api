<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property Session session
 */
class invitation extends Model
{
    protected $fillable = [
        'send_at',
        'scheduled_at',
        'code'
    ];

    protected $dates = [
        'send_at',
        'scheduled_at'
    ];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }
}
