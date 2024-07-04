<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Builders\Statistics\PlayerStatisticsQueryBuilder;

use Closure;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Modules\FootballMatch\Models\MatchEvents\RedCard;

final readonly class RedCardStatisticsBuilder extends StatisticsQueryBuilder
{
    public function applyToQuery(QueryBuilder $master_query, Closure $next): QueryBuilder
    {
        $master_query
            ->selectRaw("COALESCE(`{$this->getCTEName()}`.`total`, 0) AS `red_cards`")
            ->leftJoin($this->getCTEName(), "{$this->getCTEName()}.player_id", '=', $this->getPlayerIdReference());

        return $next($master_query);
    }

    public function getCTEName(): string
    {
        return 'red_cards';
    }

    public function getCTE(): array
    {
        return [
            $this->getCTEName() => $this->buildCTE(),
        ];
    }

    public function buildCTE(): EloquentBuilder
    {
        return RedCard::query()
            ->select('player_id')
            ->selectRaw('COUNT(*) AS `total`')
            ->groupBy('player_id')
            ->unless(empty($this->event_ids), fn(EloquentBuilder $builder) => $builder->whereIn('id', $this->event_ids));
    }

}
