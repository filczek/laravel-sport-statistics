<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Actions;

use Closure;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use Modules\FootballMatch\Collections\PlayerStatisticsCollection;
use Modules\FootballMatch\DataTransferObjects\GetPlayerStatisticsDto;
use Modules\FootballMatch\DataTransferObjects\PlayerStatisticsDto;
use Modules\FootballMatch\Filters\PlayerStatisticsOfMatchFilter\FilterType;

final class GetPlayerStatisticsAction
{
    // TODO: Refine the type of $player_statistics from QueryBuilder to a more specific type
    // that accurately represents the expected input, such as PlayerStatisticsQueryBuilder
    // or a custom interface like PlayerStatisticsQueryInterface. This will improve type safety and code clarity.
    //
    // Maybe modifying the PlayerStatisticsQueryBuilder will do the job.
    public static function execute(QueryBuilder $player_statistics, GetPlayerStatisticsDto $data): PlayerStatisticsCollection
    {
        /** @var Collection $statistics */
        $statistics = app(Pipeline::class)
            ->send($player_statistics)
            ->through([
                ...self::filters($data),
                ...self::sorters($data),
            ])
            ->thenReturn()
            ->get();

        // TODO should it include Player model like previously?

        return new PlayerStatisticsCollection(
            $statistics
                ->transform(fn ($statistics) => PlayerStatisticsDto::fromArray((array) $statistics))
                ->toBase()
                ->all()
        );
    }

    private static function filters(GetPlayerStatisticsDto $data): array
    {
        return collect($data)
            ->filter(fn ($value, string $key) => FilterType::tryFrom($key))
            ->map(fn ($value, string $key) => FilterType::from($key)->createFilter($data))
            ->values()
            ->all();
    }

    private static function sorters(GetPlayerStatisticsDto $data): array
    {
        // TODO move to Sorter class
        $x = new class($data) {
            public function __construct(
                private GetPlayerStatisticsDto $payload
            ) {
            }

            public function handle(QueryBuilder $builder, Closure $next): QueryBuilder
            {
                $sort_by = $this->payload->sort_by;
                $sort_direction = $this->payload->sort_dir ?? "desc";

                if ($sort_by) {
                    $builder->orderBy($sort_by, $sort_direction);
                }

                return $next($builder);
            }
        };

        return [$x];
    }
}
