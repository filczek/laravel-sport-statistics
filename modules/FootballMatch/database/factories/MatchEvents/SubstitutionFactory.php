<?php

namespace Modules\FootballMatch\database\factories\MatchEvents;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\FootballMatch\Models\MatchEvents\Substitution;
use Modules\FootballMatch\Models\Player;
use Modules\FootballMatch\Models\Team;

class SubstitutionFactory extends Factory
{
    protected $model = Substitution::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'player_in_id' => Player::factory(),
            'player_out_id' => Player::factory(),
            'team_id' => Team::factory(),
        ];
    }
}
