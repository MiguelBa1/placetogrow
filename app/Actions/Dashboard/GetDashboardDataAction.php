<?php

namespace App\Actions\Dashboard;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class GetDashboardDataAction
{
    public function execute(string $startDate, string $endDate): array
    {
        $cacheKeyPrefix = "dashboard_data_{$startDate}_{$endDate}_";

        $data = [
            'paymentsOverTime' => $this->getCachedData($cacheKeyPrefix . 'payments_over_time', 'CALL get_payments_over_time(?, ?)', [$startDate, $endDate]),
            'topMicrositesByTransactions' => $this->getCachedData($cacheKeyPrefix . 'top_microsites_by_transactions', 'CALL get_top_microsites_by_transactions(?, ?)', [$startDate, $endDate]),
            'invoiceDistribution' => $this->getCachedData($cacheKeyPrefix . 'invoice_distribution', 'CALL get_invoice_distribution(?, ?)', [$startDate, $endDate]),
            'subscriptionDistribution' => $this->getCachedData($cacheKeyPrefix . 'subscription_distribution', 'CALL get_subscription_distribution(?, ?)', [$startDate, $endDate]),
            'approvedTransactionsByMicrositeType' => $this->getCachedData($cacheKeyPrefix . 'approved_transactions_by_microsite_type', 'CALL get_approved_transactions_by_microsite_type(?, ?)', [$startDate, $endDate]),
        ];

        $lastUpdated = Cache::get('dashboard_cache_timestamp', Carbon::now()->toDateTimeString());

        Cache::put('dashboard_cache_timestamp', Carbon::now()->toDateTimeString(), 86400);

        return [
            'data' => $data,
            'lastUpdated' => $lastUpdated,
        ];
    }

    private function getCachedData(string $cacheKey, string $query, array $bindings)
    {
        return Cache::remember($cacheKey, 86400, function () use ($query, $bindings) {
            return DB::select($query, $bindings);
        });
    }
}
