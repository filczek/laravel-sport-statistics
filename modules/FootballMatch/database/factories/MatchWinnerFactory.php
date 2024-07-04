<?php

namespace Modules\FootballMatch\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\FootballMatch\Enums\Winner\WinType;
use Modules\FootballMatch\Models\FootballMatch;
use Modules\FootballMatch\Models\Winner;
use Modules\FootballMatch\Models\Team;

class MatchWinnerFactory extends Factory
{
    protected $model = Winner::class;

    public function definition(): array
    {
        return [
            'match_id' => FootballMatch::factory(),
            'team_id' => Team::factory(),
            'type' => $this->faker->randomElement(WinType::cases())->value,
        ];
    }

    public function regular(): MatchWinnerFactory
    {
        return $this->state(fn (array $attributes) => [
            'type' => WinType::Regular,
        ]);
    }
}
