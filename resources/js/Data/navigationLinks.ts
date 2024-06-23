export const getNavigationLinks = (t: (key: string) => string) => [
    { name: 'dashboard', route: 'dashboard', label: t('layouts.authenticatedLayout.dashboard') },
    { name: 'microsites', route: 'microsites.index', label: t('layouts.authenticatedLayout.microsites') },
];

export const getGuestLinks = (t: (key: string) => string) => [
    { name: 'login', route: 'login', label: t('layouts.guestLayout.login') },
];
