export type PaymentOverTime = {
    day: string;
    currency: string;
    total_amount: string;
};

export type TopMicrositesByTransactions = {
    microsite_name: string;
    transaction_count: number;
};

export type InvoiceDistribution = {
    status: string;
    invoice_count: number;
};

export type SubscriptionDistribution = {
    status: string;
    subscription_count: number;
};

export type ApprovedTransactionsByMicrositeType = {
    microsite_type: string;
    approved_transactions: number;
};

export type ChartData = {
    paymentsOverTime: PaymentOverTime[];
    topMicrositesByTransactions: TopMicrositesByTransactions[];
    invoiceDistribution: InvoiceDistribution[];
    subscriptionDistribution: SubscriptionDistribution[];
    approvedTransactionsByMicrositeType: ApprovedTransactionsByMicrositeType[];
};
