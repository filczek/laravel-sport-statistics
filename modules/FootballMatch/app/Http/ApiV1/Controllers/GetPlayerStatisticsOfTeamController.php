<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Http\ApiV1\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\FootballMatch\Actions\GetPlayerStatisticsAction;
use Modules\FootballMatch\Builders\Statistics\TeamPlayerStatisticsQueryBuilder;
use Modules\FootballMatch\DataTransferObjects\GetPlayerStatisticsDto;
use Modules\FootballMatch\Models\Team;

final class GetPlayerStatisticsOfTeamController extends Controller
{
    public function __invoke(Team $team, Request $request)
    {
        $data = GetPlayerStatisticsDto::fromArray($request->all());

        $builder = TeamPlayerStatisticsQueryBuilder::query()
            ->ofTeam($team)
            ->build();

        // TODO add resource
        return GetPlayerStatisticsAction::execute($builder, $data);
    }
}
