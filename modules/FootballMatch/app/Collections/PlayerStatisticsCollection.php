<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Collections;

use Illuminate\Support\Collection;
use Modules\FootballMatch\DataTransferObjects\PlayerStatisticsDto;

/**
 * @extends Collection<int, PlayerStatisticsDto>
 */
final class PlayerStatisticsCollection extends Collection
{
}
