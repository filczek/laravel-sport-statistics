<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Enums\MatchEvents\FreeKick;

enum FreeKickType: string
{
    case Direct = "direct";
    case Indirect = "indirect";
}
