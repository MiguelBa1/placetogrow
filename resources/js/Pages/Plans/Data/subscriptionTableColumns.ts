export const getPlanTableColumns = (t: (key: string) => string) => [
    { key: 'name', label: t('plans.index.table.name') },
    { key: 'price', label: t('plans.index.table.price') },
    { key: 'total_duration', label: t('plans.index.table.total_duration') },
    { key: 'billing_frequency', label: t('plans.index.table.billing_frequency') },
    { key: 'created_at', label: t('plans.index.table.created_at') },
    { key: 'actions', label: t('plans.index.table.actions') },
];
