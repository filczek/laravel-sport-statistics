<?php

use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;
use Modules\FootballMatch\Builders\MatchBuilder;
use Modules\FootballMatch\Models\FootballMatch;

uses(MakesHttpRequests::class);

it('gets a match', function () {
    // Given
    $match = MatchBuilder::create()->build();

    // When
    $actual = $this
        ->getJson(route('get-match', ['match' => $match]))
        ->assertSuccessful();

    // Then
    /** @var FootballMatch $original */
    $original = $actual->original;

    expect($original->id)->toBe($match->id);
});
