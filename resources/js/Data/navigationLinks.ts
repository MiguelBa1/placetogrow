const authenticatedLinks = [
    { name: 'dashboard', route: 'dashboard', label: 'dashboard', requiredPermissions: ['view_dashboard'] },
    { name: 'microsites', route: 'microsites.index', label: 'microsites', requiredPermissions: ['view_any_microsite'] },
    { name: 'transactions', route: 'transactions.index', label: 'transactions', requiredPermissions: ['view_any_transaction'] },
    { name: 'users', route: 'users.index', label: 'users', requiredPermissions: ['view_any_user'] },
    { name: 'roles', route: 'roles-permissions.index', label: 'roles', requiredPermissions: ['manage_roles'] },
];

export const getNavigationLinks = (t: (key: string) => string, requiredPermissions: string[] = []) => {
    return authenticatedLinks.filter(link => {
        if (!link.requiredPermissions) {
            return true;
        }
        return link.requiredPermissions.every(permission => requiredPermissions.includes(permission));
    }).map(link => ({
        name: link.name,
        route: link.route,
        label: t(`layouts.authenticatedLayout.${link.label}`),
    }));
};

export const getGuestLinks = (t: (key: string) => string) => [
    { name: 'login', route: 'login', label: t('layouts.guestLayout.login') },
];
