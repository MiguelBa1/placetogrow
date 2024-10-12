<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\Dashboard\GetDashboardDataAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\DashboardDataResource;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(GetDashboardDataAction $getDashboardDataAction): Response
    {
        $dashboardData = $getDashboardDataAction->execute();

        $data = (new DashboardDataResource((object) $dashboardData['data']))->toArray(request());

        return Inertia::render('Dashboard/Index', [
            'data' => $data,
            'lastUpdated' => $dashboardData['lastUpdated']
        ]);
    }
}
