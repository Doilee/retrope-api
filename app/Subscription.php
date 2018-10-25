<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SanderVanHooft\Invoicable\IsInvoicable\IsInvoicableTrait;

/**
 * App\Subscription
 *
 * @property int $id
 * @property int $client_id
 * @property string $type
 * @property \Illuminate\Support\Carbon $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\SanderVanHooft\Invoicable\Invoice[] $invoices
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subscription whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
