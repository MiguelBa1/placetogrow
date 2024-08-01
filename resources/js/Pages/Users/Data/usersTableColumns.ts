export const getUsersTableColumns = (t: (key: string) => string) => [
    { key: 'id', label: t('users.index.table.id') },
    { key: 'name', label: t('users.index.table.name') },
    { key: 'email', label: t('users.index.table.email') },
    { key: 'created_at', label: t('users.index.table.created_at') },
    { key: 'roles', label: t('users.index.table.roles') },
    { key: 'actions', label: t('users.index.table.actions') },
];
