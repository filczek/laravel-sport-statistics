<?php

use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;
use Modules\FootballMatch\Builders\MatchBuilder;
use Modules\FootballMatch\DataTransferObjects\PlayerStatisticsDto;
use Modules\FootballMatch\Models\Team;

uses(MakesHttpRequests::class);

it('gets match player statistics', function () {
    // Given
    $team = Team::factory()->create();
    $player = $team->players()->inRandomOrder()->first();

    $match = MatchBuilder::create()
        ->withHomeTeam($team)
        ->withGoalBy($player)
        ->withGoalBy($player)
        ->withGoalBy($player)
        ->build();

    // When
    $actual = $this
        ->getJson(route('get-statistics-of-match', ['match' => $match, 'sort_by' => 'goals_total', 'sort_dir' => 'desc']))
        ->assertSuccessful();

    // Then
    $statistics = PlayerStatisticsDto::fromArray($actual->json(0));

    expect($statistics->player_id)->toBe($player->id);
    expect($statistics->goals_total)->toBe(3);
});
