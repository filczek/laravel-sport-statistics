<?php

namespace Modules\FootballMatch\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\FootballMatch\Enums\Match\MatchScoreType;
use Modules\FootballMatch\Models\FootballMatch;
use Modules\FootballMatch\Models\Score;

class ScoreFactory extends Factory
{
    protected $model = Score::class;

    public function definition(): array
    {
        return [
            'match_id' => FootballMatch::factory(),
            'type' => $this->faker->randomElement(MatchScoreType::cases())->value,

            'home' => $this->faker->randomNumber(),
            'away' => $this->faker->randomNumber(),
        ];
    }

    public function regular(int $home, int $away): ScoreFactory
    {
        return $this->state(fn (array $attributes) => [
            'type' => MatchScoreType::Regular,
            'home' => $home,
            'away' => $away,
        ]);
    }

    public function penalties(int $home, int $away): ScoreFactory
    {
        return $this->state(fn (array $attributes) => [
            'type' => MatchScoreType::PenaltyShootout,
            'home' => $home,
            'away' => $away,
        ]);
    }
}
