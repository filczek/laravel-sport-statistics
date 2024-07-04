<?php

use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Modules\FootballMatch\Builders\MatchBuilder;
use Modules\FootballMatch\Models\FootballMatch;
use Modules\FootballMatch\Models\Team;

uses(RefreshDatabase::class);
uses(MakesHttpRequests::class);

it('gets team player statistics', function () {
    // Given
    $home_team = Team::factory()->create();
    $home_team_player = $home_team->players()->inRandomOrder()->first();

    $away_team = Team::factory()->create();
    $away_team_player = $away_team->players()->inRandomOrder()->first();

    $match = MatchBuilder::create()
        ->withHomeTeam($home_team)
        ->withAwayTeam($away_team)
        ->withGoalBy($home_team_player)
        ->withGoalBy($home_team_player)
        ->withGoalBy($home_team_player)
        ->withYellowCardTo($home_team_player)
        ->withRedCardTo($away_team_player)
        ->build();

    // When
    $goals_total = $this
        ->get(route('get-player-statistics-of-team', ['team' => $home_team, 'sort_by' => 'goals_total', 'sort_dir' => 'desc']))
        ->assertStatus(200);

    $most_yellow_cards = $this
        ->get(route('get-player-statistics-of-team', ['team' => $home_team, 'sort_by' => 'yellow_cards', 'sort_dir' => 'desc']))
        ->assertStatus(200);

    $most_red_cards = $this
        ->get(route('get-player-statistics-of-team', ['team' => $away_team, 'sort_by' => 'red_cards', 'sort_dir' => 'desc']))
        ->assertStatus(200);

    // Then
    /** @var Collection $original */
    $player_with_most_goals = $goals_total->original->first();
    expect($player_with_most_goals['player']->id)->toBe($home_team_player->id);
    expect($player_with_most_goals['stats']->goals_total)->toBe(3);

    $player_with_most_yellow_cards = $most_yellow_cards->original->first();
    expect($player_with_most_yellow_cards['player']->id)->toBe($home_team_player->id);
    expect($player_with_most_yellow_cards['stats']->yellow_cards)->toBe(1);

    $player_with_most_red_cards = $most_red_cards->original->first();
    expect($player_with_most_red_cards['player']->id)->toBe($away_team_player->id);
    expect($player_with_most_red_cards['stats']->red_cards)->toBe(1);
});
