export const getSubscriptionTableColumns = (t: (key: string) => string) => [
    { key: 'subscription_name', label: t('customerSubscriptions.show.table.subscriptionName') },
    { key: 'microsite_name', label: t('customerSubscriptions.show.table.micrositeName') },
    { key: 'price', label: t('customerSubscriptions.show.table.price') },
    { key: 'start_date', label: t('customerSubscriptions.show.table.startDate') },
    { key: 'end_date', label: t('customerSubscriptions.show.table.endDate') },
    { key: 'status', label: t('customerSubscriptions.show.table.status') },
    { key: 'actions', label: t('customerSubscriptions.show.table.actions') },
];
