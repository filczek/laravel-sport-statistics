<?php

namespace Modules\FootballMatch\Http\ApiV1\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\FootballMatch\Actions\GetPlayerStatisticsAction;
use Modules\FootballMatch\Builders\Statistics\TeamPlayerStatisticsQueryBuilder;
use Modules\FootballMatch\Models\Team;

class GetPlayerStatisticsOfTeamController extends Controller
{
    public function __invoke(Team $team, Request $request)
    {
        $builder = TeamPlayerStatisticsQueryBuilder::query()
            ->ofTeam($team)
            ->build();

        return GetPlayerStatisticsAction::execute($builder, $request->all());
    }
}
