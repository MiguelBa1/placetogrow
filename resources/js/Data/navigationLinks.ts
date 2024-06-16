export const getNavigationLinks = (t: (key: string) => string) => [
    { name: 'dashboard', route: 'dashboard', label: t('layouts.authenticatedLayout.dashboard') },
];
