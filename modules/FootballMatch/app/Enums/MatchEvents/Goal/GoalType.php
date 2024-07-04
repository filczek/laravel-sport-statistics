<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Enums\MatchEvents\Goal;

enum GoalType: string
{
    /** A goal scored during the natural flow of the game. */
    case OpenPlay = 'open_play';

    /** A goal scored using the head. */
    case Header = 'header';

    /** A goal scored by striking the ball before it hits the ground. */
    case Volley = 'volley';

    /** A goal scored by striking the ball just after it has bounced. */
    case HalfVolley = 'half_volley';

    /** A goal scored directly from a free kick. */
    case FreeKick = 'free_kick';

    /** A goal scored from a penalty kick. */
    case Penalty = 'penalty';

    /** A goal accidentally scored by a player into their own net. */
    case Own = 'own';

    /** A goal scored from very close range, often into an open goal. */
    case TapIn = 'tap_in';

    /** A goal scored from a considerable distance outside the penalty area. */
    case LongRange = 'long_range';

    /** A goal scored by lofting the ball over the goalkeeper. */
    case ChipLob = 'chip_lob';

    /** A goal scored during a chaotic situation in front of the goal, often involving multiple players. */
    case Scramble = 'scramble';

    /** A goal scored by a player who dribbles past multiple opponents before scoring. */
    case Solo = 'solo';

    /** A goal scored from a planned set piece (e.g., corner kick, free kick). */
    case SetPiece = 'set_piece';

    /** A goal scored after the ball rebounds off the goalkeeper or the post. */
    case Rebound = 'rebound';

    /** A spectacular goal scored with an overhead kick. */
    case BicycleKick = 'bicycle_kick';

    /** A goal that goes in after deflecting off another player, intentionally or unintentionally. */
    case Deflection = 'deflection';
}
