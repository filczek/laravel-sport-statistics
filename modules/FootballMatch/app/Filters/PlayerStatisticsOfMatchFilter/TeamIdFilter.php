<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Filters\PlayerStatisticsOfMatchFilter;

use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Modules\FootballMatch\Models\Player;

final class TeamIdFilter extends Filter
{
    public function handle(Builder $query, Closure $next): Builder
    {
        $team_ids = Arr::wrap(Arr::get($this->payload, 'team_id', []));

        $query->where(function (Builder $q) use ($team_ids) {
            $player_ids_of_teams = Player::whereIn('team_id', $team_ids)
                ->select('id');

            $q->whereIn('player_id', $player_ids_of_teams);
        });

        return $next($query);
    }
}
