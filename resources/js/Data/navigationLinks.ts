export const getNavigationLinks = (t: (key: string) => string) => [
    { name: 'dashboard', route: 'dashboard', label: t('layouts.authenticatedLayout.dashboard') },
];

export const getGuestLinks = (t: (key: string) => string) => [
    { name: 'login', route: 'login', label: t('layouts.guestLayout.login') },
];
