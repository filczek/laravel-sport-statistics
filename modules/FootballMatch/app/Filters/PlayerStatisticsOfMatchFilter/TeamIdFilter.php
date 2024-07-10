<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Filters\PlayerStatisticsOfMatchFilter;

use Closure;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Validator;
use Modules\FootballMatch\Models\Player;

final class TeamIdFilter extends Filter
{
    public function handle(QueryBuilder $query, Closure $next): QueryBuilder
    {
        $team_ids = $this->payload->team_id;
        Validator::make(['items' => $team_ids], ['items.*' => 'uuid'])->validate();

        if (!empty($team_ids)) {
            $query->where(function (QueryBuilder $q) use ($team_ids) {
                // TODO this can be quite expensive at large scale, fix this
                $player_ids_of_teams = Player::whereIn('team_id', $team_ids)
                    ->select('id');

                $q->whereIn('player_id', $player_ids_of_teams);
            });
        }

        return $next($query);
    }
}
