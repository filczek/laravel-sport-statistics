<?php

namespace Modules\FootballMatch\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\FootballMatch\Models\Player;
use Modules\FootballMatch\Models\Team;

class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
        ];
    }

    public function configure(): TeamFactory
    {
        return $this->afterCreating(function (Team $team) {
            $players = Player::factory()
                ->count(11)
                ->make(['team_id' => $team->id])
                ->toArray();

            Player::insert($players);
        });
    }
}
