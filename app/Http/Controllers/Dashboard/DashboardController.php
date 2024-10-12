<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\DashboardDataResource;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $data = [
            'paymentsOverTime' => Cache::remember('payments_over_time', 86400, function () {
                return DB::select('CALL get_payments_over_time()');
            }),
            'topMicrositesByTransactions' => Cache::remember('top_microsites_by_transactions', 86400, function () {
                return DB::select('CALL get_top_microsites_by_transactions()');
            }),
            'invoiceDistribution' => Cache::remember('invoice_distribution', 86400, function () {
                return DB::select('CALL get_invoice_distribution()');
            }),
            'subscriptionDistribution' => Cache::remember('subscription_distribution', 86400, function () {
                return DB::select('CALL get_subscription_distribution()');
            }),
            'approvedTransactionsByMicrositeType' => Cache::remember('approved_transactions_by_microsite_type', 86400, function () {
                return DB::select('CALL get_approved_transactions_by_microsite_type()');
            }),
        ];

        return Inertia::render('Dashboard/Index', (new DashboardDataResource((object) $data))->toArray(request()));
    }
}
