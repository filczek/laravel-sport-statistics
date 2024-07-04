<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Enums\Winner;

enum WinType: string
{
    case Regular = "regular";

    case PenaltyShootout = "penalty_shootout";

    case Walkover = "walkover";
}
