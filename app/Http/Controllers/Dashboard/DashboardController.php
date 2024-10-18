<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\Dashboard\GetDashboardDataAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\DashboardDateFilterRequest;
use App\Http\Resources\Dashboard\DashboardDataResource;
use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(DashboardDateFilterRequest $request, GetDashboardDataAction $getDashboardDataAction): Response
    {
        $startDate = $request->input('start_date', Carbon::now()->subMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        $dashboardData = $getDashboardDataAction->execute($startDate, $endDate);

        $data = (new DashboardDataResource((object) $dashboardData['data']))->toArray(request());

        return Inertia::render('Dashboard/Index', [
            'data' => $data,
            'lastUpdated' => $dashboardData['lastUpdated'],
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
