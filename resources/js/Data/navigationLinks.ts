const authenticatedNavigationLinks = [
    { name: 'home', route: 'home', label: 'home' },
    { name: 'dashboard', route: 'dashboard', label: 'dashboard', requiredPermissions: ['view_dashboard'] },
    { name: 'microsites', route: 'microsites.index', label: 'microsites', requiredPermissions: ['view_any_microsite'] },
    { name: 'transactions', route: 'transactions.index', label: 'transactions', requiredPermissions: ['view_any_transaction'] },
    { name: 'users', route: 'users.index', label: 'users', requiredPermissions: ['view_any_user'] },
    { name: 'roles', route: 'roles-permissions.index', label: 'roles', requiredPermissions: ['manage_roles'] },
];

const guestNavigationLinks = [
    { name: 'home', route: 'home', label: 'home' },
    { name: 'invoices', route: 'invoices.index', label: 'invoices' },
]

export const getNavigationLinks = (t: (key: string) => string, requiredPermissions: string[] = [], isAuthenticated: boolean = false) => {
    if (!isAuthenticated) {
        return guestNavigationLinks.map(link => ({
            name: link.name,
            route: link.route,
            label: t(`layouts.guestLayout.${link.label}`),
        }));
    }

    return authenticatedNavigationLinks
        .filter(link => {
            // If it doesn't require permissions, show the link
            if (!link.requiredPermissions) {
                return true;
            }
            // If it has required permissions, check if the user has them
            return link.requiredPermissions.every(permission => requiredPermissions.includes(permission));
        })
        .map(link => ({
            name: link.name,
            route: link.route,
            label: t(`layouts.authenticatedLayout.${link.label}`),
        }));};

export const getGuestDropdownLinks = (t: (key: string) => string) => [
    { name: 'login', route: 'login', label: t('layouts.guestLayout.login') },
];
