<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Filters\PlayerStatisticsOfMatchFilter;

use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;

final class OffsetFilter extends Filter
{
    public function handle(Builder $query, Closure $next): Builder
    {
        $offset = Arr::get($this->payload, 'offset');

        if (null !== $offset) {
            $query->offset($offset);
        }

        return $next($query);
    }
}
