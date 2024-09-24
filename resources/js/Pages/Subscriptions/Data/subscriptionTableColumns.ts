export const getSubscriptionTableColumns = (t: (key: string) => string) => [
    { key: 'subscription_name', label: t('subscriptions.show.table.subscriptionName') },
    { key: 'microsite_name', label: t('subscriptions.show.table.micrositeName') },
    { key: 'price', label: t('subscriptions.show.table.price') },
    { key: 'start_date', label: t('subscriptions.show.table.startDate') },
    { key: 'end_date', label: t('subscriptions.show.table.endDate') },
    { key: 'status', label: t('subscriptions.show.table.status') },
    { key: 'actions', label: t('subscriptions.show.table.actions') },
];
