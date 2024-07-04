<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Actions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Modules\FootballMatch\Enums\Match\MatchEventType;
use Modules\FootballMatch\Models\MatchEvent;
use Modules\FootballMatch\Models\MatchEvents\GoalEvent;
use Modules\FootballMatch\Models\MatchEvents\RedCard;
use Modules\FootballMatch\Models\MatchEvents\YellowCard;

final class FilterMatchEventsAction
{
    /** @return Collection<int, MatchEvent> */
    public static function execute(array $filter): Collection
    {
        $match_ids = Arr::wrap($filter['match_id'] ?? []);

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

        $events = $events->whereIn('match_id', $match_ids);

        foreach (($filter['filter'] ?? []) as $event_type => $filters) {
            match (MatchEventType::from($event_type)) {
                MatchEventType::Goal => $events->whereHasMorph(
                    'eventable',
                    [GoalEvent::class],
                    fn (Builder $morphTo) => $morphTo->filter($filters)
                ),
                MatchEventType::YellowCard => $events->whereHasMorph(
                    'eventable',
                    [YellowCard::class],
                    fn (Builder $morphTo) => $morphTo->filter($filters)
                ),
                MatchEventType::RedCard => $events->whereHasMorph(
                    'eventable',
                    [RedCard::class],
                    fn (Builder $morphTo) => $morphTo->filter($filters)
                ),
            };
        }

        $sort_dir = $filter['sort_dir'] ?? 'desc';
        $events->orderBy('timestamp', $sort_dir);

        $events = $events->get();

        return $events;
    }
}
