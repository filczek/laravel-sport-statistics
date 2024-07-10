<?php

use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;
use Modules\FootballMatch\Builders\MatchBuilder;
use Modules\FootballMatch\Enums\Match\MatchEventType;
use Modules\FootballMatch\Enums\MatchEvents\Goal\BodyPartType;
use Modules\FootballMatch\Enums\MatchEvents\Goal\GoalType;
use Modules\FootballMatch\Models\Team;

uses(MakesHttpRequests::class);

it('returns all goals of a match', function () {
    $match = MatchBuilder::create()
        ->withScore(3, 0)
        ->withYellowCards(1, 0)
        ->withRedCards(0, 2)
        ->build();

    $this
        ->getJson(route('filter-events-of-match', ['match' => $match, 'type' => MatchEventType::Goal]))
        ->assertSuccessful()
        ->assertJsonCount(3);
});

it('returns all goals of specific team', function () {
    $team = Team::factory()->create();
    $player = $team->players()->inRandomOrder()->first();

    $match = MatchBuilder::create()
        ->withHomeTeam($team)
        ->withGoalBy($player)
        ->withGoalBy($player)
        ->withGoalBy($player)
        ->withGoalBy($player)
        ->withGoalBy($player)
        ->withGoalBy($player)
        ->withYellowCards(1, 0)
        ->withRedCards(0, 2)
        ->build();

    $request = [
        'match' => $match,
        'type' => MatchEventType::Goal,
        'filter' => [
            'goal' => [
                'team_id' => $team->id,
            ]
        ]
    ];

    $this
        ->getJson(route('filter-events-of-match', $request))
        ->assertSuccessful()
        ->assertJsonCount(6)
        ->assertJsonFragment(['team_id' => $team->id])
        ->assertJsonFragment(['player_id' => $player->id]);
});

it('returns goals for a specific phase', function () {
    $match = MatchBuilder::create()
        ->withScore(3, 0)
        ->withYellowCards(1, 0)
        ->withRedCards(0, 2)
        ->build();

    $this
        ->getJson(route('filter-events-of-match', ['match' => $match, 'type' => MatchEventType::Goal]))
        ->assertSuccessful()
        ->assertJsonCount(3);
})->skip('Add phase to the match builder');

it('returns goals of a specific type', function () {
    $team = Team::factory()->create();
    $player = $team->players()->inRandomOrder()->first();

    $match = MatchBuilder::create()
        ->withHomeTeam($team)
        ->withGoalBy($player, GoalType::FreeKick)
        ->withGoalBy($player, GoalType::FreeKick)
        ->withGoalBy($player, GoalType::Header)
        ->withYellowCards(1, 0)
        ->withRedCards(0, 2)
        ->build();

    $request = [
        'match' => $match,
        'type' => MatchEventType::Goal,
        'filter' => [
            'goal' => [
                'goal_type' => GoalType::FreeKick
            ]
        ]
    ];

    $this
        ->getJson(route('filter-events-of-match', $request))
        ->assertSuccessful()
        ->assertJsonCount(2)
        ->assertJsonFragment(['team_id' => $team->id])
        ->assertJsonFragment(['player_id' => $player->id]);
});


it('returns goals of a specific body part', function () {
    $team = Team::factory()->create();
    $player = $team->players()->inRandomOrder()->first();

    $match = MatchBuilder::create()
        ->withHomeTeam($team)
        ->withGoalBy($player, GoalType::Header, BodyPartType::Head)
        ->withGoalBy($player, GoalType::FreeKick, BodyPartType::Other)
        ->withGoalBy($player, GoalType::Header, BodyPartType::Other)
        ->withYellowCards(1, 0)
        ->withRedCards(0, 2)
        ->build();

    $request = [
        'match' => $match,
        'type' => MatchEventType::Goal,
        'filter' => [
            'goal' => [
                'body_part' => BodyPartType::Head
            ]
        ]
    ];

    $this
        ->getJson(route('filter-events-of-match', $request))
        ->assertSuccessful()
        ->assertJsonCount(1)
        ->assertJsonFragment(['team_id' => $team->id])
        ->assertJsonFragment(['player_id' => $player->id]);
});

it('returns no goals when there are none', function () {
    $match = MatchBuilder::create()
        ->withScore(0, 0)
        ->build();

    $request = [
        'match' => $match,
        'type' => MatchEventType::Goal,
    ];

    $this->getJson(route('filter-events-of-match', $request))
        ->assertSuccessful()
        ->assertJsonCount(0);
});

it('returns an error for invalid match ID', function () {
    $this->getJson(route('filter-events-of-match', ['match' => 999999]))
        ->assertNotFound();
});

it('returns an error for invalid event type', function () {
    $match = MatchBuilder::create()
        ->withScore(0, 0)
        ->build();

    $request = [
        'match' => $match,
        'type' => 'some_random_type',
    ];

    $this->getJson(route('filter-events-of-match', $request))
        ->assertStatus(422);
});
