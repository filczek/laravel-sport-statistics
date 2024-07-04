<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Filters\PlayerStatisticsOfMatchFilter;

use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Modules\FootballMatch\Models\Player;

final class PlayerPositionFilter extends Filter
{
    public function handle(Builder $query, Closure $next): Builder
    {
        $positions = Arr::wrap(Arr::get($this->payload, 'position', []));

        $query->where(function (Builder $q) use ($positions) {
            $player_ids_of_positions = Player::whereIn('position', $positions)
                ->select('id');

            $q->whereIn('player_id', $player_ids_of_positions);
        });

        return $next($query);
    }
}
