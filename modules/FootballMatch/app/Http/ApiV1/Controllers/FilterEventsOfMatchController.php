<?php

namespace Modules\FootballMatch\Http\ApiV1\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\FootballMatch\Actions\FilterMatchEventsAction;

class FilterEventsOfMatchController extends Controller
{
    public function __invoke(string $id, Request $request)
    {
        $payload = [
            'match_id' => $id,
            ...$request->all(),
        ];

        return FilterMatchEventsAction::execute($payload);
    }
}
