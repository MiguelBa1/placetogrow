export const getSubscriptionTableColumns = (t: (key: string) => string) => [
    { key: 'name', label: t('subscriptions.index.table.name') },
    { key: 'price', label: t('subscriptions.index.table.price') },
    { key: 'total_duration', label: t('subscriptions.index.table.total_duration') },
    { key: 'billing_frequency', label: t('subscriptions.index.table.billing_frequency') },
    { key: 'created_at', label: t('subscriptions.index.table.created_at') },
    { key: 'actions', label: t('subscriptions.index.table.actions') },
];
