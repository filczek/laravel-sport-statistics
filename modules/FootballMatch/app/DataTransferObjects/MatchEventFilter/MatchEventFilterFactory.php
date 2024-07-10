<?php

declare(strict_types=1);

namespace Modules\FootballMatch\DataTransferObjects\MatchEventFilter;

use Modules\FootballMatch\Enums\Match\MatchEventType;

final readonly class MatchEventFilterFactory
{
    public static function fromArray(array $array): array
    {
        $filters = [];

        foreach ($array as $key => $value) {
            $type = MatchEventType::from($key);
            $filter = self::createFilter($type, $value);

            $filters[$key] = $filter;
        }

        return $filters;
    }

    private static function createFilter(MatchEventType $type, array $data): MatchEventFilter
    {
        /** @var class-string<MatchEventFilter> $class */
        $class = match ($type) {
            MatchEventType::Goal => GoalEventFilterDto::class,
            MatchEventType::YellowCard => YellowCardEventFilterDto::class,
            MatchEventType::RedCard => RedCardEventFilterDto::class,
        };

        return $class::fromArray($data);
    }
}
