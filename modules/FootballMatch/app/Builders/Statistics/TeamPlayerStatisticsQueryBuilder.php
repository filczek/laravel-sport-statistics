<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Builders\Statistics;

use Illuminate\Database\Query\Builder as QueryBuilder;
use Modules\FootballMatch\Models\FootballMatch;
use Modules\FootballMatch\Models\MatchEvent;
use Modules\FootballMatch\Models\Player;
use Modules\FootballMatch\Models\Team;

class TeamPlayerStatisticsQueryBuilder
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

    public function ofTeam(Team ...$teams): self
    {
        $team_ids = collect($teams)
            ->map(fn (Team $team) => $team->id)
            ->toArray();

        $match_ids = FootballMatch::query()
            ->select('id')
            ->whereIn('home_team_id', $team_ids)
            ->orWHereIn('away_team_id', $team_ids)
            ->get();

        $event_ids = MatchEvent::query()
            ->whereIn('match_id', $match_ids)
            ->pluck('id')
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
