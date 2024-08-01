export const getFieldsTableColumns = (t: (key: string) => string) => [
    { key: 'name', label: t('microsites.show.fields.name') },
    { key: 'label', label: t('microsites.show.fields.label') },
    { key: 'type', label: t('microsites.show.fields.type') },
    { key: 'actions', label: t('microsites.show.fields.actions') },
];
