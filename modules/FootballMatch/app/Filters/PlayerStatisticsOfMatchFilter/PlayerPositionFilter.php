<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Filters\PlayerStatisticsOfMatchFilter;

use Closure;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\FootballMatch\Enums\Player\PlayerPosition;
use Modules\FootballMatch\Models\Player;

final class PlayerPositionFilter extends Filter
{
    public function handle(QueryBuilder $query, Closure $next): QueryBuilder
    {
        $player_positions = $this->payload->position;
        Validator::make(['items' => $player_positions], ['items.*' => Rule::enum(PlayerPosition::class)])->validate();

        if (!empty($player_positions)) {
            $query->where(function (QueryBuilder $q) use ($player_positions) {
                // TODO this can be quite expensive at large scale, fix this
                $player_ids_of_positions = Player::whereIn('position', $player_positions)
                    ->select('id');

                $q->whereIn('player_id', $player_ids_of_positions);
            });
        }

        return $next($query);
    }
}
