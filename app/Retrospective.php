<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Retrospective
 *
 * @property int $id
 * @property \Carbon\Carbon|null $voting_starts_at
 * @property \Carbon\Carbon|null $expires_at
 * @property boolean is_public
 * @property \Carbon\Carbon|null $starts_at
 * Class Session
 * @package App
 * @property int $player_id
 * @property string $feedback
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Action[] $actions
 * @property-read \App\User $host
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Player[] $players
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Retrospective whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Retrospective whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Retrospective whereFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Retrospective whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Retrospective wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Retrospective whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $host_id
 * @property string $name
 * @property int $is_public
 * @property \Illuminate\Support\Carbon|null $scheduled_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Retrospective whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Retrospective whereHostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Retrospective whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Retrospective whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Retrospective whereScheduledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Retrospective whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Retrospective whereVotingStartsAt($value)
 */
class Retrospective extends Model
{
    protected $fillable = [
        'name',
        'is_public',
        'scheduled_at',
        'starts_at',
        'voting_starts_at',
        'expires_at',
    ];

    protected $dates = [
        'scheduled_at',
        'starts_at',
        'voting_starts_at',
        'expires_at',
    ];

    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function actions()
    {
        return $this->hasManyThrough(Action::class, Player::class);
    }

    public function isExpired()
    {
        return $this->expires_at ? $this->expires_at->isPast() : false;
    }

    public function start($timer = null)
    {
        $this->starts_at = now()->toDateTimeString();
        $this->voting_starts_at = now()->addSeconds($timer ?? 0)->toDateTimeString();
        $this->expires_at = now()->addSeconds($timer * 1.5 ?? 0)->toDateTimeString();

        return $this->save();
    }
}
