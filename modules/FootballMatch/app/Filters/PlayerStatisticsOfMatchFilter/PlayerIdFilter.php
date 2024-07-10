<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Filters\PlayerStatisticsOfMatchFilter;

use Closure;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Validator;

final class PlayerIdFilter extends Filter
{
    public function handle(QueryBuilder $query, Closure $next): QueryBuilder
    {
        $player_ids = $this->payload->player_id;
        Validator::make(['items' => $player_ids], ['items.*' => 'uuid'])->validate();

        if (!empty($player_ids)) {
            $query->whereIn('player_id', $player_ids);
        }

        return $next($query);
    }
}
