<?php

use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Modules\FootballMatch\Builders\MatchBuilder;
use Modules\FootballMatch\Models\FootballMatch;
use Modules\FootballMatch\Models\Team;

uses(RefreshDatabase::class);
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
        ->get(route('get-statistics-of-match', ['match' => $match, 'sort_by' => 'goals_total', 'sort_dir' => 'desc']))
        ->assertStatus(200);

    // Then
    /** @var Collection $original */
    $original = $actual->original;
    $first = $original->first();

    expect($first['player']->id)->toBe($player->id);
    expect($first['stats']->goals_total)->toBe(3);
});
