<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Builders\Statistics;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use Modules\FootballMatch\Builders\Statistics\PlayerStatisticsQueryBuilder\GoalStatisticsBuilder;
use Modules\FootballMatch\Builders\Statistics\PlayerStatisticsQueryBuilder\RedCardStatisticsBuilder;
use Modules\FootballMatch\Builders\Statistics\PlayerStatisticsQueryBuilder\StatisticsQueryBuilder;
use Modules\FootballMatch\Builders\Statistics\PlayerStatisticsQueryBuilder\YellowCardStatisticsBuilder;

class PlayerStatisticsQueryBuilder
{
    // TODO use query instead
    private array $event_ids = [];

    public static function query(): self
    {
        return new self();
    }

    public function eventIds(array $events_ids): self
    {
        $this->event_ids = $events_ids;

        return $this;
    }

    public function build(): QueryBuilder
    {
        $event_ids = $this->event_ids;

        /** @var StatisticsQueryBuilder[] $ctes */
        $ctes = [
            new GoalStatisticsBuilder($event_ids),
            new YellowCardStatisticsBuilder($event_ids),
            new RedCardStatisticsBuilder($event_ids),
        ];

        return $this->combineQueries($ctes);
    }

    private function combineQueries(array $ctes): QueryBuilder
    {
        $master_query = $this->buildBaseQuery($ctes);
        $cte_raw_sql = $this->getCTEsRawSql($ctes);
        $raw_query = "$cte_raw_sql\n{$master_query->toSql()}";

        $query = DB::query()->fromRaw("($raw_query) AS `player_statistics`");

        collect($ctes)
            ->mapWithKeys(fn (StatisticsQueryBuilder $builder) => $builder->getCTE())
            ->transform(fn (EloquentBuilder $cte) => $cte->toBase())
            ->add($master_query)
            ->each(fn (QueryBuilder $builder) => $query->mergeBindings($builder));

        return $query;
    }

    private function buildBaseQuery(array $ctes): QueryBuilder
    {
        $master_query = DB::query()
            ->select('p.id as player_id')
            ->from('football_match__players', 'p');

        return app(Pipeline::class)
            ->send($master_query)
            ->through($ctes)
            ->via('applyToQuery')
            ->thenReturn();
    }

    /** @var StatisticsQueryBuilder[] $ctes */
    private function getCTEsRawSql(array $ctes): string
    {
        $raw_sqls = collect($ctes)
            ->mapWithKeys(fn (StatisticsQueryBuilder $builder) => $builder->getCTE())
            ->map(fn($cte, string $key) => "`$key` AS ({$cte->toSql()})")
            ->join(",\n");

        return empty($ctes) ? $raw_sqls : "WITH $raw_sqls";
    }
}
