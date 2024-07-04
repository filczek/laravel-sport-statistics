<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Enums\MatchEvents\Penalty;

enum PenaltyType: string
{
    case Scored = "scored";
    case Missed = "missed";
}
