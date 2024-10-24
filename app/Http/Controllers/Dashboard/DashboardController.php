<?php

namespace App\Http\Controllers\Dashboard;

use App\Actions\Dashboard\GetDashboardDataAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\DashboardDateFilterRequest;
use App\Http\Resources\Dashboard\DashboardDataResource;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(DashboardDateFilterRequest $request, GetDashboardDataAction $getDashboardDataAction): Response|RedirectResponse
    {
        $startDate = $request->input('start_date', Carbon::now()->subMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        if (Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) > 31) {
            return redirect()->back()->withErrors([
                'date_range' => __('validation.date_range_exceeded'),
            ]);
        }

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
