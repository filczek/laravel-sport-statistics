<?php

namespace Modules\FootballMatch\database\factories\MatchEvents;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\FootballMatch\Models\MatchEvents\RedCard;
use Modules\FootballMatch\Models\Player;

class RedCardFactory extends Factory
{
    protected $model = RedCard::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'player_id' => Player::factory(),
        ];
    }
}
