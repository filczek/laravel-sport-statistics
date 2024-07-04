<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Enums\MatchEvents\Goal;

enum BodyPartType: string
{
    case Head = "head";
    case LeftFoot = "left_foot";
    case RightFoot = "right_foot";
    case LeftLeg = "left_leg";
    case RightLeg = "right_leg";
    case Chest = "chest";
    case Knee = "knee";
    case Other = "other";
}
