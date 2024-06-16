export const getUserDropdownLinks = (t: (key: string) => string) => [
    { name: 'profile', route: 'profile.edit', label: t('layouts.authenticatedLayout.userDropdown.profile') },
    { name: 'logout', route: 'logout', label: t('layouts.authenticatedLayout.userDropdown.logOut'), method: 'post', as: 'button' },
];
