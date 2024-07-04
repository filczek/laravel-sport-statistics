<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Builders\Statistics\PlayerStatisticsQueryBuilder;

use Closure;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Modules\FootballMatch\Enums\MatchEvents\Goal\BodyPartType;
use Modules\FootballMatch\Models\MatchEvents\GoalEvent;

final readonly class GoalStatisticsBuilder extends StatisticsQueryBuilder
{
    public function applyToQuery(QueryBuilder $master_query, Closure $next): QueryBuilder
    {
        $columns = collect($this->getUsedColumns());

        $columns->each(fn($column) => $master_query->selectRaw("COALESCE({$this->getCTEName()}.`{$column}`, 0) AS `{$column}`"));
        $master_query->leftJoin($this->getCTEName(), "{$this->getCTEName()}.player_id", '=', $this->getPlayerIdReference());

        return $next($master_query);
    }

    public function getCTEName(): string
    {
        return 'goals';
    }

    public function getCTE(): array
    {
        return [
            $this->getCTEName() => $this->buildGoalsCte(),
        ];
    }

    public function getUsedColumns(): array
    {
        $body_parts = collect(BodyPartType::cases())
            ->map(fn(BodyPartType $type) => "goals_{$type->value}");

        return collect(['goals_total'])
            ->merge($body_parts)
            ->toArray();
    }

    public function buildGoalsCte(): EloquentBuilder
    {
        $cte = GoalEvent::query()
            ->select('player_id')
            ->selectRaw('COUNT(*) AS goals_total')
            ->groupBy('player_id')
            ->unless(empty($this->event_ids), fn(EloquentBuilder $builder) => $builder->whereIn('id', $this->event_ids));

        $this->selectBodyPartColumns($cte);

        return $cte;
    }

    public function selectBodyPartColumns(EloquentBuilder $query): void
    {
        collect(BodyPartType::cases())
            ->map(fn(BodyPartType $type) => $type->value)
            ->each(fn($body_part) => $query->selectRaw("COUNT(CASE WHEN `body_part` = '{$body_part}' THEN 1 END) AS `goals_{$body_part}`"));
    }
}
