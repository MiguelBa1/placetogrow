export const getNavigationLinks = (t: (key: string) => string) => [
    { name: 'dashboard', route: 'dashboard', label: t('layouts.authenticatedLayout.dashboard') },
    { name: 'microsites', route: 'microsites.index', label: t('layouts.authenticatedLayout.microsites') },
    { name: 'roles', route: 'roles-permissions.index', label: t('layouts.authenticatedLayout.roles') },
    { name: 'users', route: 'users.index', label: t('layouts.authenticatedLayout.users') },
];

export const getGuestLinks = (t: (key: string) => string) => [
    { name: 'login', route: 'login', label: t('layouts.guestLayout.login') },
];
