<?php

use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Modules\FootballMatch\Builders\MatchBuilder;
use Modules\FootballMatch\Enums\Match\MatchEventType;
use Modules\FootballMatch\Models\FootballMatch;
use Modules\FootballMatch\Models\Team;

uses(RefreshDatabase::class);
uses(MakesHttpRequests::class);

it('returns three goals of a match', function () {
    // Given
    $match = MatchBuilder::create()
        ->withScore(3, 0)
        ->build();

    // When
    $actual = $this
        ->get(route('filter-events-of-match', ['match' => $match, 'type' => MatchEventType::Goal]))
        ->assertStatus(200);

    // Then
    /** @var Collection $original */
    $original = $actual->original;
    expect($original)->toHaveCount(3);

    $types = $original->pluck('type')->pluck('value')->unique()->toArray();
    expect($types)->toEqual([MatchEventType::Goal->value]);
});
