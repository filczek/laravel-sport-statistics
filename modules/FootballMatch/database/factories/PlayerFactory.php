<?php

declare(strict_types=1);

namespace Modules\FootballMatch\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\FootballMatch\Enums\Player\PlayerPosition;
use Modules\FootballMatch\Models\Player;
use Modules\FootballMatch\Models\Team;

class PlayerFactory extends Factory
{
    protected $model = Player::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'team_id' => Team::factory(),
            'name' => $this->faker->name(),
            'position' => $this->faker->randomElement(PlayerPosition::cases())->value,
        ];
    }
}
