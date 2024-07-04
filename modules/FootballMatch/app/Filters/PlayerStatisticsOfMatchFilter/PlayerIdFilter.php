<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Filters\PlayerStatisticsOfMatchFilter;

use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;

final class PlayerIdFilter extends Filter
{
    public function handle(Builder $query, Closure $next): Builder
    {
        $player_ids = Arr::wrap(Arr::get($this->payload, 'player_id', []));

        $query->whereIn('player_id', $player_ids);

        return $next($query);
    }
}
