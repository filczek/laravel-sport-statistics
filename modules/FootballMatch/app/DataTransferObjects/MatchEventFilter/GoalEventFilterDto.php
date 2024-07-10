<?php

declare(strict_types=1);

namespace Modules\FootballMatch\DataTransferObjects\MatchEventFilter;

use Illuminate\Support\Arr;

final readonly class GoalEventFilterDto extends MatchEventFilter
{
    public static function fromArray(array $array): self
    {
        $team_id = Arr::wrap(Arr::get($array, 'team_id', []));
        $player_id = Arr::wrap(Arr::get($array, 'player_id', []));
        $goal_type = Arr::wrap(Arr::get($array, 'goal_type', []));
        $body_part = Arr::wrap(Arr::get($array, 'body_part', []));

        return new self(
            team_id: $team_id,
            player_id: $player_id,
            goal_type: $goal_type,
            body_part: $body_part,
        );
    }

    public function __construct(
        public array $team_id,
        public array $player_id,
        public array $goal_type,
        public array $body_part
    ) {
    }
}
