<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Enums\Match;

use Modules\FootballMatch\Models\MatchEvents\GoalEvent;
use Modules\FootballMatch\Models\MatchEvents\RedCard;
use Modules\FootballMatch\Models\MatchEvents\Substitution;
use Modules\FootballMatch\Models\MatchEvents\YellowCard;

enum MatchEventType: string
{
//    case StartPhase = "start_phase";

//    case Corner = "corner"; // ???????
    case Goal = "goal"; // primary actor = scorer ; includes new score
    case Substitution = "substitution";
    case YellowCard = "yellow_card";
    case RedCard = "red_card";
//    case InjuryTimer = "injury_time";   // TODO injury time minutes added
//    case Penalty = "Penalty";   // primary actor = player; secondary actor = goalkeeper ; time is missing
//    case FreeKick = "free_kick";
//
//    case EndPhase = "end_phase";
//    case FullTime = "full_time";

    public function morphKey(): string
    {
        return $this->value;
    }

    public function morphValue(): string
    {
        return match ($this) {
            self::Goal => GoalEvent::class,
            self::Substitution => Substitution::class,
            self::YellowCard => YellowCard::class,
            self::RedCard => RedCard::class,
        };
    }

    public static function morphMap(): array
    {
        return collect(self::cases())
            ->map(fn (MatchEventType $type) => [$type->morphKey(), $type->morphValue()])
            ->pluck(1, 0)
            ->toArray();
    }
}
