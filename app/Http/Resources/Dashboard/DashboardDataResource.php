<?php

namespace App\Http\Resources\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class DashboardDataResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'paymentsOverTime' => $this->translatePaymentsOverTime(collect($this->paymentsOverTime)),
            'topMicrositesByTransactions' => $this->topMicrositesByTransactions,
            'invoiceDistribution' => $this->translateInvoiceDistribution(collect($this->invoiceDistribution)),
            'subscriptionDistribution' => $this->translateSubscriptionDistribution(collect($this->subscriptionDistribution)),
            'approvedTransactionsByMicrositeType' => $this->translateMicrositeTypes(collect($this->approvedTransactionsByMicrositeType)),
        ];
    }

    private function translatePaymentsOverTime(Collection $payments): Collection
    {
        return $payments->map(function ($payment): array {
            return [
                'day' => $payment->day,
                'currency' => $payment->currency,
                'total_amount' => $payment->total_amount,
            ];
        });
    }

    private function translateInvoiceDistribution(Collection $invoices): Collection
    {
        return $invoices->map(function ($invoice): array {
            return [
                'status' => __('invoices.statuses.' . $invoice->status),
                'invoice_count' => $invoice->invoice_count,
            ];
        });
    }

    private function translateSubscriptionDistribution(Collection $subscriptions): Collection
    {
        return $subscriptions->map(function ($subscription): array {
            return [
                'status' => __('subscription.statuses.' . $subscription->status),
                'subscription_count' => $subscription->subscription_count,
            ];
        });
    }

    private function translateMicrositeTypes(Collection $microsites): Collection
    {
        return $microsites->map(function ($microsite): array {
            return [
                'microsite_type' => __('microsite_types.' . $microsite->microsite_type),
                'approved_transactions' => $microsite->approved_transactions,
            ];
        });
    }
}
