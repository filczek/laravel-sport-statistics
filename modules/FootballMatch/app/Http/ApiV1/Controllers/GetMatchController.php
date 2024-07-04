<?php

namespace Modules\FootballMatch\Http\ApiV1\Controllers;

use App\Http\Controllers\Controller;
use Modules\FootballMatch\Http\ApiV1\Resources\MatchResource;
use Modules\FootballMatch\Models\FootballMatch;

class GetMatchController extends Controller
{
    public function __invoke(FootballMatch $match)
    {
        $match->load('homeTeam', 'awayTeam', 'scores', 'winners');

        return MatchResource::make($match);
    }
}