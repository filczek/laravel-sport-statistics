<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Builders\Statistics;

use Illuminate\Database\Query\Builder as QueryBuilder;
use Modules\FootballMatch\Models\FootballMatch;
use Modules\FootballMatch\Models\MatchEvent;
use Modules\FootballMatch\Models\Player;

class MatchPlayerStatisticsQueryBuilder
{
    public static function query(): self
    {
        return new self();
    }

    private function __construct(
        private array $event_ids = [],
        private array $player_ids = []
    ) {
        // TODO use query instead
    }

    public function ofMatch(FootballMatch ...$matches): self
    {
        $match_ids = collect($matches)
            ->map(fn (FootballMatch $match) => $match->id)
            ->toArray();

        $event_ids = MatchEvent::query()
            ->whereIn('match_id', $match_ids)
            ->pluck('id')
            ->toArray();

        $team_ids = collect($matches)
            ->map(fn (FootballMatch $match) => [$match->home_team_id, $match->away_team_id])
            ->flatten()
            ->unique()
            ->toArray();

        $player_ids = Player::query()
            ->whereIn('team_id', $team_ids)
            ->pluck('id')
            ->toArray();

        $this->event_ids = $event_ids;
        $this->player_ids = $player_ids;

        return $this;
    }

    public function build(): QueryBuilder
    {
        return PlayerStatisticsQueryBuilder::query()
            ->eventIds($this->event_ids)
            ->build()
            ->unless(empty($this->player_ids), fn (QueryBuilder $builder) => $builder->whereIn('player_id', $this->player_ids));
    }
}
