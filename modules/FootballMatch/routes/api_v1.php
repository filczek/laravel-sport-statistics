<?php

use Illuminate\Support\Facades\Route;
use Modules\FootballMatch\Http\ApiV1\Controllers\GetPlayerStatisticsOfTeamController;
use Modules\FootballMatch\Http\ApiV1\Controllers\GetStatisticsOfMatchController;
use Modules\FootballMatch\Http\ApiV1\Controllers\GetMatchController;
use Modules\FootballMatch\Http\ApiV1\Controllers\FilterEventsOfMatchController;
use Modules\FootballMatch\Http\ApiV1\Controllers\PlayerOfTheGameController;

Route::prefix('teams')->group(function () {
    Route::get('/{team}/players/statistics', GetPlayerStatisticsOfTeamController::class)->name('get-player-statistics-of-team');
});

Route::prefix('matches')->group(function () {
    Route::get('/{match}', GetMatchController::class)->name('get-match');
    Route::get('/{match}/statistics', GetStatisticsOfMatchController::class)->name('get-statistics-of-match');
    Route::get('/{match}/events', FilterEventsOfMatchController::class)->name('filter-events-of-match');
    Route::get('/{match}/player_of_the_game', PlayerOfTheGameController::class)->name('player-of-the-game');
});
