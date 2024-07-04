<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Enums\Match;

enum MatchPhase: string
{
    case FirstHalf = "first_half";
    case SecondHalf = "second_half";
    case ExtraTimeFirstHalf = "extra_time_first_half";
    case ExtraTimeSecondHalf = "extra_time_second_half";
    case Penalty = "Penalty";
}
