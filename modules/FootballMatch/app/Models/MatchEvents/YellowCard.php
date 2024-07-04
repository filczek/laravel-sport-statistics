<?php

namespace Modules\FootballMatch\Models\MatchEvents;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Arr;
use Modules\FootballMatch\database\factories\MatchEvents\YellowCardFactory;
use Modules\FootballMatch\Models\MatchEvent;
use Modules\FootballMatch\Models\Player;

class YellowCard extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'player_id',
    ];

    public $timestamps = false;
    protected $table = 'football_match__events__yellow_cards';

    protected static function newFactory(): YellowCardFactory
    {
        return YellowCardFactory::new();
    }

    public function scopeFilter(Builder $query, array $filter): Builder
    {
        if (isset($filter['player_id'])) {
            $query->whereIn('player_id', Arr::wrap(['player_id']));
        }

        return $query;
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function event(): MorphOne
    {
        return $this->morphOne(MatchEvent::class, 'eventable', 'type', 'id');
    }
}
