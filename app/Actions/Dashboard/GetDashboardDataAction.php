<?php

namespace App\Actions\Dashboard;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GetDashboardDataAction
{
    public function execute(): array
    {
        $data = [
            'paymentsOverTime' => $this->getCachedData('payments_over_time', 'CALL get_payments_over_time()'),
            'topMicrositesByTransactions' => $this->getCachedData('top_microsites_by_transactions', 'CALL get_top_microsites_by_transactions()'),
            'invoiceDistribution' => $this->getCachedData('invoice_distribution', 'CALL get_invoice_distribution()'),
            'subscriptionDistribution' => $this->getCachedData('subscription_distribution', 'CALL get_subscription_distribution()'),
            'approvedTransactionsByMicrositeType' => $this->getCachedData('approved_transactions_by_microsite_type', 'CALL get_approved_transactions_by_microsite_type()'),
        ];

        $lastUpdated = Cache::get('dashboard_cache_timestamp', Carbon::now()->toDateTimeString());

        Cache::put('dashboard_cache_timestamp', Carbon::now()->toDateTimeString(), 86400);

        return [
            'data' => $data,
            'lastUpdated' => $lastUpdated,
        ];
    }

    private function getCachedData(string $cacheKey, string $query)
    {
        return Cache::remember($cacheKey, 86400, function () use ($query) {
            return DB::select($query);
        });
    }
}
