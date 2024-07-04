<?php

namespace Modules\FootballMatch\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\FootballMatch\Enums\Match\MatchPhase;
use Modules\FootballMatch\Enums\Match\MatchStatus;
use Modules\FootballMatch\Enums\Match\MatchType;
use Modules\FootballMatch\Models\FootballMatch;
use Modules\FootballMatch\Models\Team;

class FootballMatchFactory extends Factory
{
    protected $model = FootballMatch::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(MatchType::cases())->value,
            'phase' => $this->faker->randomElement(MatchPhase::cases())->value,
            'status' => $this->faker->randomElement(MatchStatus::cases())->value,

            'home_team_id' => Team::factory(),
            'away_team_id' => Team::factory(),
        ];
    }
}
