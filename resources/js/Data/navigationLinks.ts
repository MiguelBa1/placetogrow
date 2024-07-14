export const getNavigationLinks = (t: (key: string) => string) => [
    { name: 'dashboard', route: 'dashboard', label: t('layouts.authenticatedLayout.dashboard') },
    { name: 'microsites', route: 'microsites.index', label: t('layouts.authenticatedLayout.microsites') },
    { name: 'roles', route: 'roles-permissions.index', label: t('layouts.authenticatedLayout.roles') },
];

export const getGuestLinks = (t: (key: string) => string) => [
    { name: 'login', route: 'login', label: t('layouts.guestLayout.login') },
];
