<?php

namespace Modules\FootballMatch\Models\MatchEvents;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\FootballMatch\database\factories\MatchEvents\GoalEventFactory;
use Modules\FootballMatch\DataTransferObjects\MatchEventFilter\GoalEventFilterDto;
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

    public function scopeFilter(EloquentBuilder $query, GoalEventFilterDto $filter): EloquentBuilder
    {
        if (false === empty($filter->team_id)) {
            $query->whereIn('team_id', $filter->team_id);
        }

        if (false === empty($filter->player_id)) {
            $query->whereIn('player_id', $filter->player_id);
        }

        if (false === empty($filter->goal_type)) {
            $query->whereIn('goal_type', $filter->goal_type);
        }

        if (false === empty($filter->body_part)) {
            $query->whereIn('body_part', $filter->body_part);
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
