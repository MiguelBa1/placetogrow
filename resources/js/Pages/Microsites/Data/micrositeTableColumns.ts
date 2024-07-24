export const getMicrositeTableColumns = (t: (key: string) => string) => [
    { key: 'id', label: t('microsites.index.table.id') },
    { key: 'name', label: t('microsites.index.table.name') },
    { key: 'category', label: t('microsites.index.table.category') },
    { key: 'type', label: t('microsites.index.table.type') },
    { key: 'responsible_name', label: t('microsites.index.table.responsible_name') },
    { key: 'payment_currency', label: t('microsites.index.table.payment_currency') },
    { key: 'payment_expiration', label: t('microsites.index.table.payment_expiration') },
    { key: 'actions', label: t('microsites.index.table.actions') },
];
