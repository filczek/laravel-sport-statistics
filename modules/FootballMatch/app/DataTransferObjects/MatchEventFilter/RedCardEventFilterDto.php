<?php

declare(strict_types=1);

namespace Modules\FootballMatch\DataTransferObjects\MatchEventFilter;

use Illuminate\Support\Arr;

final readonly class RedCardEventFilterDto extends MatchEventFilter
{
    public static function fromArray(array $array): self
    {
        $player_id = Arr::wrap(Arr::get($array, 'player_id', []));

        return new self(
            player_id: $player_id,
        );
    }

    public function __construct(
        public array $player_id,
    ) {
    }
}
