<?php

namespace Modules\FootballMatch\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\FootballMatch\Models\FootballMatch;
use Modules\FootballMatch\Models\Player;
use Modules\FootballMatch\Models\PlayerOfTheMatch;

class PlayerOfTheMatchFactory extends Factory
{
    protected $model = PlayerOfTheMatch::class;

    public function definition(): array
    {
        return [
            'match_id' => FootballMatch::factory(),
            'player_id' => Player::factory(),
        ];
    }
}
