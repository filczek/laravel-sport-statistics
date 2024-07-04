<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Enums\Match;

enum MatchScoreType: string
{
    case Regular = "regular";
    case PenaltyShootout = "penalty_shootout";
}
