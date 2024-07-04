<?php

namespace Modules\FootballMatch\database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Modules\FootballMatch\Enums\Match\MatchEventType;
use Modules\FootballMatch\Enums\Match\MatchPhase;
use Modules\FootballMatch\Models\FootballMatch;
use Modules\FootballMatch\Models\MatchEvent;
use Modules\FootballMatch\Models\MatchEvents\GoalEvent;

class MatchEventFactory extends Factory
{
    protected $model = MatchEvent::class;

    public function definition(): array
    {
        /** @var MatchEventType $event_type */
        $event_type = $this->faker->randomElement(MatchEventType::cases());

        /** @var Model $eventable */
        $eventable = $event_type->morphValue();

        return [
            'match_id' => FootballMatch::factory(),

            'id' => $eventable::factory(),
            'type' => $event_type->morphKey(),

            'phase' => $this->faker->randomElement(MatchPhase::cases())->value,
            'timestamp' => Carbon::now(),
        ];
    }
}
