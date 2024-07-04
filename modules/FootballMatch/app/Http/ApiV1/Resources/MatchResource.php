<?php

namespace Modules\FootballMatch\Http\ApiV1\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\FootballMatch\Models\FootballMatch;

/** @mixin FootballMatch */
class MatchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'phase' => $this->phase,
            'status' => $this->status,

            'home_team' => $this->whenLoaded('homeTeam'),
            'away_team' => $this->whenLoaded('awayTeam'),

            'winners' => $this->whenLoaded('winners', function ($winners) {
                return $winners
                    ->keyBy(fn ($winner) => $winner->type->value);
            }),

            'score' => $this->whenLoaded('scores', function ($scores) {
                return $scores
                    ->keyBy(fn ($score) => $score->type->value)
                    ->map(fn ($score) => ['home' => $score->home, 'away' => $score->away]);
            }),
        ];
    }
}
