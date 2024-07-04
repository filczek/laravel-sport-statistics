<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Actions;

use Closure;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Arr;
use Modules\FootballMatch\Filters\PlayerStatisticsOfMatchFilter\FilterType;
use Modules\FootballMatch\Models\Player;

final class GetPlayerStatisticsAction
{
    public static function execute(QueryBuilder $player_statistics, array $payload)
    {
        $statistics = app(Pipeline::class)
            ->send($player_statistics)
            ->through([
                ...self::filters($payload),
                ...self::sorters($payload),
            ])
            ->thenReturn()
            ->get();

        $players = Player::whereIn('id', $statistics->pluck('player_id'))
            ->get();

        // TODO create PlayerStatisticsResource
        return $statistics
            ->map(fn ($statistics) => [
                'player' => $players->find($statistics->player_id),
                'stats' => $statistics
            ]);
    }

    private static function filters(array $payload): array
    {
        return collect($payload)
            ->filter(fn ($value, string $key) => FilterType::tryFrom($key))
            ->map(fn ($value, string $key) => FilterType::from($key)->createFilter($payload))
            ->values()
            ->all();
    }

    private static function sorters(array $payload): array
    {
        // TODO move to Sorter class
        $x = new class($payload) {
            public function __construct(
                private array $payload
            ) {
            }

            public function handle(QueryBuilder $builder, Closure $next): QueryBuilder
            {
                $sort_by = Arr::get($this->payload, 'sort_by');
                $sort_direction = Arr::get($this->payload, 'sort_dir', 'desc');

                if ($sort_by) {
                    $builder->orderBy($sort_by, $sort_direction);
                }

                return $next($builder);
            }
        };

        return [$x];
    }
}
