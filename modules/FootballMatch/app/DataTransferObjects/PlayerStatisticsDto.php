<?php

declare(strict_types=1);

namespace Modules\FootballMatch\DataTransferObjects;

final readonly class PlayerStatisticsDto
{
    public static function fromArray(array $array): self
    {
        return new self(...$array);
    }

    public function __construct(
        public string $player_id,
        public int $goals_total,
        public int $goals_head,
        public int $goals_left_foot,
        public int $goals_right_foot,
        public int $goals_left_leg,
        public int $goals_right_leg,
        public int $goals_chest,
        public int $goals_knee,
        public int $goals_other,
        public int $yellow_cards,
        public int $red_cards,
    ) {
    }
}
