export const getTransactionTableColumns = (t: (key: string) => string) => [
    { key: 'reference', label: t('transactions.index.table.reference') },
    { key: 'microsite', label: t('transactions.index.table.microsite') },
    { key: 'status', label: t('transactions.index.table.status') },
    { key: 'amount', label: t('transactions.index.table.amount') },
    { key: 'payment_date', label: t('transactions.index.table.payment_date') },
    { key: 'actions', label: t('transactions.index.table.actions') },
];
