<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Filters\PlayerStatisticsOfMatchFilter;

use Closure;
use Illuminate\Database\Query\Builder as QueryBuilder;

final class OffsetFilter extends Filter
{
    public function handle(QueryBuilder $query, Closure $next): QueryBuilder
    {
        $offset = $this->payload->offset;

        if (null !== $offset) {
            $query->offset($offset);
        }

        return $next($query);
    }
}
