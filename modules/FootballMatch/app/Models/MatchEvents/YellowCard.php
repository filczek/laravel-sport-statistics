<?php

namespace Modules\FootballMatch\Models\MatchEvents;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\FootballMatch\database\factories\MatchEvents\YellowCardFactory;
use Modules\FootballMatch\DataTransferObjects\MatchEventFilter\YellowCardEventFilterDto;
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

    public function scopeFilter(EloquentBuilder $query, YellowCardEventFilterDto $filter): EloquentBuilder
    {
        if (false === empty($filter->player_id)) {
            $query->whereIn('player_id', $filter->player_id);
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
