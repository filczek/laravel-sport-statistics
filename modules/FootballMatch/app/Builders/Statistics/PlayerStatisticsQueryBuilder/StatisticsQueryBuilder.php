<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Builders\Statistics\PlayerStatisticsQueryBuilder;

abstract readonly class StatisticsQueryBuilder implements StatisticsQueryBuilderInterface
{
    public function __construct(
        protected array $event_ids,
    ) {
    }

    public function getPlayerIdReference(): string
    {
        return "p.id";
    }
}
