<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Enums\Team;

enum TeamType: string
{
    case National = "national";
    case Club = "club";
    case Youth = "youth";
}
