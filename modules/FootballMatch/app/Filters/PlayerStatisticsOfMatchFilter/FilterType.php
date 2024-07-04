<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Filters\PlayerStatisticsOfMatchFilter;

enum FilterType: string
{
    case PlayerId = "player_id";
    case TeamId = "team_id";
    case PlayerPosition = "position";

    case Limit = "limit";
    case Offset = "offset";

    public function createFilter(array $payload): Filter
    {
        return match ($this) {
            self::PlayerId => new PlayerIdFilter($payload),
            self::TeamId => new TeamIdFilter($payload),
            self::PlayerPosition => new PlayerPositionFilter($payload),
            self::Limit => new LimitFilter($payload),
            self::Offset => new OffsetFilter($payload),
        };
    }
}
