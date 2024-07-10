<?php

declare(strict_types=1);

namespace Modules\FootballMatch\DataTransferObjects\MatchEventFilter;

abstract readonly class MatchEventFilter
{
    abstract public static function fromArray(array $array): self;
}
