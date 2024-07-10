<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Collections;

use Illuminate\Support\Collection;
use Modules\FootballMatch\Models\MatchEvent;

/**
 * @extends Collection<int, MatchEvent>
 */
final class MatchEventCollection extends Collection
{
}
