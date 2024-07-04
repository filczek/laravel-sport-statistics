<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Filters\PlayerStatisticsOfMatchFilter;

use Closure;
use Illuminate\Database\Query\Builder;

abstract class Filter
{
    public function __construct(
        protected array $payload
    ) {
    }

    abstract public function handle(Builder $query, Closure $next): Builder;
}
