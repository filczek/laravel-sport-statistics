<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Actions;

use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\FootballMatch\Collections\MatchEventCollection;
use Modules\FootballMatch\DataTransferObjects\MatchEventFilter\MatchEventFilterDto;
use Modules\FootballMatch\Enums\Match\MatchEventType;
use Modules\FootballMatch\Enums\Match\MatchPhase;
use Modules\FootballMatch\Models\MatchEvent;
use Modules\FootballMatch\Models\MatchEvents\GoalEvent;
use Modules\FootballMatch\Models\MatchEvents\RedCard;
use Modules\FootballMatch\Models\MatchEvents\YellowCard;
use RuntimeException;

final class FilterMatchEventsAction
{
    public static function execute(MatchEventFilterDto $data): MatchEventCollection
    {
        throw_if(empty($data->match_id), RuntimeException::class, 'Match ID is required and cannot be empty');
        Validator::make(['items' => $data->type], ['items.*' => [Rule::enum(MatchEventType::class)]])->validate();
        Validator::make(['items' => $data->phase], ['items.*' => [Rule::enum(MatchPhase::class)]])->validate();

        // TODO optimize to fetch the same relations in one query
        $events = MatchEvent::query()
            ->with(['eventable' => function (MorphTo $morphTo) {
                $morphTo->morphWith([
                    GoalEvent::class => ['team', 'player'],
                    YellowCard::class => ['player'],
                    RedCard::class => ['player'],
                ]);
            }]);

        // TODO filter strategy & pipeline
        /** @see GetPlayerStatisticsAction */

        $events = $events
            ->whereIn('match_id', $data->match_id)
            ->unless(empty($data->type), fn (EloquentBuilder $query) => $query->whereIn('type', $data->type))
            ->unless(empty($data->phase), fn (EloquentBuilder $query) => $query->whereIn('phase', $data->phase))
            ->orderBy('timestamp', $data->sort_dir ?? "desc");

        foreach ($data->filter as $event_type => $filters) {
            match (MatchEventType::from($event_type)) {
                MatchEventType::Goal => $events->whereHasMorph(
                    'eventable',
                    [GoalEvent::class],
                    fn (EloquentBuilder $morphTo) => $morphTo->filter($filters)
                ),
                MatchEventType::YellowCard => $events->whereHasMorph(
                    'eventable',
                    [YellowCard::class],
                    fn (EloquentBuilder $morphTo) => $morphTo->filter($filters)
                ),
                MatchEventType::RedCard => $events->whereHasMorph(
                    'eventable',
                    [RedCard::class],
                    fn (EloquentBuilder $morphTo) => $morphTo->filter($filters)
                ),
            };
        }

        return new MatchEventCollection(
            $events
                ->get()
                ->toBase()
                ->all()
        );
    }
}
