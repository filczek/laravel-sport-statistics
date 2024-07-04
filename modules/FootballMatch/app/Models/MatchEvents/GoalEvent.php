<?php

namespace Modules\FootballMatch\Models\MatchEvents;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Arr;
use Modules\FootballMatch\database\factories\MatchEvents\GoalEventFactory;
use Modules\FootballMatch\Enums\MatchEvents\Goal\BodyPartType;
use Modules\FootballMatch\Enums\MatchEvents\Goal\GoalType;
use Modules\FootballMatch\Models\MatchEvent;
use Modules\FootballMatch\Models\Player;
use Modules\FootballMatch\Models\Team;

class GoalEvent extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'team_id',
        'player_id',
        'goal_type',
        'body_part'
    ];
    public $timestamps = false;
    protected $table = 'football_match__events__goals';

    protected static function newFactory(): GoalEventFactory
    {
        return GoalEventFactory::new();
    }

    protected function casts(): array
    {
        return [
            'goal_type' => GoalType::class,
            'body_part' => BodyPartType::class,
        ];
    }

    public function scopeFilter(Builder $query, array $filter = []): Builder
    {
        if (isset($filter['team_id'])) {
            $query->whereIn('team_id', Arr::wrap($filter['team_id']));
        }

        if (isset($filter['player_id'])) {
            $query->whereIn('player_id', Arr::wrap($filter['player_id']));
        }

        if (isset($filter['goal_type'])) {
            $query->whereIn('goal_type', Arr::wrap($filter['goal_type']));
        }

        if (isset($filter['body_part'])) {
            $query->whereIn('body_part', Arr::wrap($filter['body_part']));
        }

        return $query;
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function event(): MorphOne
    {
        return $this->morphOne(MatchEvent::class, 'eventable', 'type', 'id');
    }
}
