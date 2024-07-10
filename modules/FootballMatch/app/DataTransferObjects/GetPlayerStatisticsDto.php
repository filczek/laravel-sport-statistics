<?php

declare(strict_types=1);

namespace Modules\FootballMatch\DataTransferObjects;

use Illuminate\Support\Arr;

final readonly class GetPlayerStatisticsDto
{
    public static function fromArray(array $array): self
    {
        $team_id = Arr::wrap(Arr::get($array, 'team_id', []));
        $player_id = Arr::wrap(Arr::get($array, 'player_id', []));
        $position = Arr::wrap(Arr::get($array, 'position', []));

        $limit = Arr::get($array, 'limit');
        $offset = Arr::get($array, 'offset');

        $sort_by = Arr::get($array, 'sort_by');
        $sort_dir = Arr::get($array, 'sort_dir');

        return new self(
            team_id: $team_id,
            player_id: $player_id,
            position: $position,
            limit: $limit,
            offset: $offset,
            sort_by: $sort_by,
            sort_dir: $sort_dir
        );
    }

    public function __construct(
        public array $team_id,
        public array $player_id,
        public array $position,
        public ?int $limit,
        public ?int $offset,
        public ?string $sort_by,
        public ?string $sort_dir,
    ) {
    }
}
