<?php

use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\FootballMatch\Builders\MatchBuilder;
use Modules\FootballMatch\Models\FootballMatch;

uses(RefreshDatabase::class);
uses(MakesHttpRequests::class);

it('gets a match', function () {
    // Given
    $match = MatchBuilder::create()->build();

    // When
    $actual = $this
        ->get(route('get-match', ['match' => $match]))
        ->assertStatus(200);

    // Then
    /** @var FootballMatch $original */
    $original = $actual->original;

    expect($original->id)->toBe($match->id);
});
