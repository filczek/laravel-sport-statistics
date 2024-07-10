<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Http\ApiV1\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\FootballMatch\Actions\GetPlayerStatisticsAction;
use Modules\FootballMatch\Builders\Statistics\MatchPlayerStatisticsQueryBuilder;
use Modules\FootballMatch\DataTransferObjects\GetPlayerStatisticsDto;
use Modules\FootballMatch\Models\FootballMatch;

final class GetStatisticsOfMatchController extends Controller
{
    public function __invoke(FootballMatch $match, Request $request)
    {
        $data = GetPlayerStatisticsDto::fromArray($request->all());

        $builder = MatchPlayerStatisticsQueryBuilder::query()
            ->ofMatch($match)
            ->build();

        // TODO add resource
        return GetPlayerStatisticsAction::execute($builder, $data);
    }
}
