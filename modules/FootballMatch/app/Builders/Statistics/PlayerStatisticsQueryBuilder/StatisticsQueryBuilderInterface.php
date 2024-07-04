<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Builders\Statistics\PlayerStatisticsQueryBuilder;

use Closure;
use Illuminate\Database\Query\Builder as QueryBuilder;

interface StatisticsQueryBuilderInterface
{
    public function applyToQuery(QueryBuilder $master_query, Closure $next): QueryBuilder;
    public function getCTE(): array;
    public function getCTEName(): string;
}
