export const getRoleTableColumns = (t: (key: string) => string) => [
    { key: 'id', label: t('rolePermissions.index.table.id') },
    { key: 'name', label: t('rolePermissions.index.table.name') },
    { key: 'actions', label: t('rolePermissions.index.table.actions') },
];
