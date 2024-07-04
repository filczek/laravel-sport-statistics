<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Enums\Player;

enum PlayerPosition: string
{
    case Goalkeeper = "goalkeeper";
    case Defender = "defender";
    case Midfielder = "midfielder";
    case Forward = "forward";
}
