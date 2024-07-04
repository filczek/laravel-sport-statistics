<?php

namespace Modules\FootballMatch\database\factories\MatchEvents;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\FootballMatch\Models\MatchEvents\YellowCard;
use Modules\FootballMatch\Models\Player;

class YellowCardFactory extends Factory
{
    protected $model = YellowCard::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'player_id' => Player::factory(),
        ];
    }
}
