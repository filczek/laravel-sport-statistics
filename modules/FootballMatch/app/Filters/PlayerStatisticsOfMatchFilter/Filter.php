<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Filters\PlayerStatisticsOfMatchFilter;

use Closure;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Modules\FootballMatch\DataTransferObjects\GetPlayerStatisticsDto;

abstract class Filter
{
    public function __construct(
        protected GetPlayerStatisticsDto $payload
    ) {
    }

    abstract public function handle(QueryBuilder $query, Closure $next): QueryBuilder;
}
