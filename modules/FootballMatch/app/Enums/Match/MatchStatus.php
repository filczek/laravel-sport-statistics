<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Enums\Match;

enum MatchStatus: string
{
    case Upcoming = "upcoming";
    case Live = "live";
    case Finished = "finished";
    case Canceled = "canceled";
}
