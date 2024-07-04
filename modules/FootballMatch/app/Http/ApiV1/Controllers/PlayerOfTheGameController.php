<?php

namespace Modules\FootballMatch\Http\ApiV1\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Modules\FootballMatch\Models\FootballMatch;

class PlayerOfTheGameController extends Controller
{
    public function __invoke(FootballMatch $match)
    {
        $player = $match->playerOfTheMatch()
            ->with('player')->get()
            ->first();

        if (is_null($player)) {
            return response()->json(null, Response::HTTP_NOT_FOUND);
        }

        $player = $player->player;

        return $player;
    }
}
