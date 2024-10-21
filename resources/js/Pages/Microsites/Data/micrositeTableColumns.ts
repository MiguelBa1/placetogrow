export const getMicrositeTableColumns = (t: (key: string) => string) => [
    { key: 'name', label: t('microsites.index.table.name') },
    { key: 'category', label: t('microsites.index.table.category') },
    { key: 'type', label: t('microsites.index.table.type') },
    { key: 'responsible_name', label: t('microsites.index.table.responsible_name') },
    { key: 'actions', label: t('microsites.index.table.actions') },
];
