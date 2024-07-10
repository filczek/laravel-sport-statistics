<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Http\ApiV1\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\FootballMatch\Actions\FilterMatchEventsAction;
use Modules\FootballMatch\DataTransferObjects\MatchEventFilter\MatchEventFilterDto;
use Modules\FootballMatch\Models\FootballMatch;

final class FilterEventsOfMatchController extends Controller
{
    public function __invoke(FootballMatch $match, Request $request)
    {
        $filter = MatchEventFilterDto::fromArray(['match_id' => $match->id, ...$request->all()]);

        // TODO add resource
        return FilterMatchEventsAction::execute($filter);
    }
}
