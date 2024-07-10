<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Filters\PlayerStatisticsOfMatchFilter;

use Closure;
use Illuminate\Database\Query\Builder as QueryBuilder;

final class LimitFilter extends Filter
{
    public function handle(QueryBuilder $query, Closure $next): QueryBuilder
    {
        $limit = $this->payload->limit;

        if (null !== $limit) {
            $query->limit($limit);
        }

        return $next($query);
    }
}
