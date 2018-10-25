<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SanderVanHooft\Invoicable\IsInvoicable\IsInvoicableTrait;

class Subscription extends Model
{
    use IsInvoicableTrait;

    protected $fillable = [
        'type',
        'expires_at',
    ];

    const TYPES = [
        'trial', 'pro', 'enterprise'
    ];

    protected $dates = [
        'expires_at'
    ];

    public function client()
    {
        $this->belongsTo(Client::class);
    }
}
