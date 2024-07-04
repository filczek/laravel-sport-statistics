<?php

namespace Modules\FootballMatch\database\factories\MatchEvents;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\FootballMatch\Enums\MatchEvents\Goal\BodyPartType;
use Modules\FootballMatch\Enums\MatchEvents\Goal\GoalType;
use Modules\FootballMatch\Models\MatchEvents\GoalEvent;
use Modules\FootballMatch\Models\Player;
use Modules\FootballMatch\Models\Team;

class GoalEventFactory extends Factory
{
    protected $model = GoalEvent::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'player_id' => Player::factory(),
            'team_id' => Team::factory(),

            'goal_type' => $this->faker->randomElement(GoalType::cases())->value,
            'body_part' => $this->faker->randomElement(BodyPartType::cases())->value,
        ];
    }
}
