<?php

declare(strict_types=1);

namespace Modules\FootballMatch\DataTransferObjects\MatchEventFilter;

use Illuminate\Support\Arr;

final readonly class MatchEventFilterDto
{
    public static function fromArray(array $array): self
    {
        $match_id = Arr::wrap(Arr::get($array, 'match_id'));
        $type = Arr::wrap(Arr::get($array, 'type'));
        $phase = Arr::wrap(Arr::get($array, 'phase'));

        $filter = Arr::get($array, 'filter', []);
        $filter = MatchEventFilterFactory::fromArray($filter);

        $sort_dir = Arr::get($array, 'sort_dir');

        return new self(
            match_id: $match_id,
            type: $type,
            phase: $phase,
            filter: $filter,
            sort_dir: $sort_dir,
        );
    }

    public function __construct(
        public array $match_id,
        public array $type,
        public array $phase,
        /** @var MatchEventFilter[] $filter */
        public array $filter,
        public ?string $sort_dir,
    ) {
    }
}
