<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Filters\PlayerStatisticsOfMatchFilter;

use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;

final class LimitFilter extends Filter
{
    public function handle(Builder $query, Closure $next): Builder
    {
        $limit = Arr::get($this->payload, 'limit');

        if (null !== $limit) {
            $query->limit($limit);
        }

        return $next($query);
    }
}
