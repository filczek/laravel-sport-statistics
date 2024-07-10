<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Filters\PlayerStatisticsOfMatchFilter;

use Modules\FootballMatch\DataTransferObjects\GetPlayerStatisticsDto;

enum FilterType: string
{
    case PlayerId = "player_id";
    case TeamId = "team_id";
    case PlayerPosition = "position";

    case Limit = "limit";
    case Offset = "offset";

    public function createFilter(GetPlayerStatisticsDto $data): Filter
    {
        return match ($this) {
            self::PlayerId => new PlayerIdFilter($data),
            self::TeamId => new TeamIdFilter($data),
            self::PlayerPosition => new PlayerPositionFilter($data),
            self::Limit => new LimitFilter($data),
            self::Offset => new OffsetFilter($data),
        };
    }
}
