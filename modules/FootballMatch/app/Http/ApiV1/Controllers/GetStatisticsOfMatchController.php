<?php

namespace Modules\FootballMatch\Http\ApiV1\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\FootballMatch\Actions\GetPlayerStatisticsAction;
use Modules\FootballMatch\Builders\Statistics\MatchPlayerStatisticsQueryBuilder;
use Modules\FootballMatch\Models\FootballMatch;

class GetStatisticsOfMatchController extends Controller
{
    public function __invoke(FootballMatch $match, Request $request)
    {
        $builder = MatchPlayerStatisticsQueryBuilder::query()
            ->ofMatch($match)
            ->build();

        return GetPlayerStatisticsAction::execute($builder, $request->all());
    }
}
