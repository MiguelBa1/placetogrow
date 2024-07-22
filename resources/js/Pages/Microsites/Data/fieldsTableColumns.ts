export const getFieldsTableColumns = (t: (key: string) => string) => [
    { key: 'name', label: t('microsites.edit.fields.name') },
    { key: 'label', label: t('microsites.edit.fields.label') },
    { key: 'type', label: t('microsites.edit.fields.type') },
    { key: 'actions', label: t('microsites.edit.fields.actions') },
];
