<?php

use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;
use Modules\FootballMatch\Builders\MatchBuilder;
use Modules\FootballMatch\DataTransferObjects\PlayerStatisticsDto;
use Modules\FootballMatch\Models\Team;

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
        ->getJson(route('get-player-statistics-of-team', ['team' => $home_team, 'sort_by' => 'goals_total', 'sort_dir' => 'desc']))
        ->assertSuccessful();

    $most_yellow_cards = $this
        ->getJson(route('get-player-statistics-of-team', ['team' => $home_team, 'sort_by' => 'yellow_cards', 'sort_dir' => 'desc']))
        ->assertSuccessful();

    $most_red_cards = $this
        ->getJson(route('get-player-statistics-of-team', ['team' => $away_team, 'sort_by' => 'red_cards', 'sort_dir' => 'desc']))
        ->assertSuccessful();

    // Then
    $player_with_most_goals = PlayerStatisticsDto::fromArray($goals_total->json(0));
    expect($player_with_most_goals->player_id)->toBe($home_team_player->id);
    expect($player_with_most_goals->goals_total)->toBe(3);

    $player_with_most_yellow_cards = PlayerStatisticsDto::fromArray($most_yellow_cards->json(0));
    expect($player_with_most_yellow_cards->player_id)->toBe($home_team_player->id);
    expect($player_with_most_yellow_cards->yellow_cards)->toBe(1);

    $player_with_most_red_cards = PlayerStatisticsDto::fromArray($most_red_cards->json(0));
    expect($player_with_most_red_cards->player_id)->toBe($away_team_player->id);
    expect($player_with_most_red_cards->red_cards)->toBe(1);
});
