<?php

use App\Constants\Permission;

return [
    Permission::VIEW_ANY_MICROSITE->value => 'View any microsite',
    Permission::VIEW_MICROSITE->value => 'View microsite',
    Permission::CREATE_MICROSITE->value => 'Create microsite',
    Permission::UPDATE_MICROSITE->value => 'Update microsite',
    Permission::DELETE_MICROSITE->value => 'Delete microsite',
    Permission::RESTORE_MICROSITE->value => 'Restore microsite',

    Permission::VIEW_ANY_PLAN->value => 'View any plan',
    Permission::VIEW_PLAN->value => 'View plan',
    Permission::CREATE_PLAN->value => 'Create plan',
    Permission::UPDATE_PLAN->value => 'Update plan',
    Permission::DELETE_PLAN->value => 'Delete plan',
    Permission::RESTORE_PLAN->value => 'Restore plan',

    Permission::VIEW_ANY_USER->value => 'View any user',
    Permission::UPDATE_USER_ROLE->value => 'Update user role',

    Permission::MANAGE_ROLES->value => 'Manage roles',
    Permission::VIEW_DASHBOARD->value => 'View dashboard',

    Permission::VIEW_ANY_INVOICE->value => 'View any invoice',
    Permission::CREATE_INVOICE->value => 'Create invoice',
    Permission::IMPORT_INVOICE->value => 'Import invoice',

    Permission::VIEW_ANY_TRANSACTION->value => 'View any transaction',
    Permission::VIEW_TRANSACTION->value => 'View transaction',

    'group' => [
        'microsite' => 'Microsite',
        'user' => 'User',
        'role' => 'Role',
        'plan' => 'Plan',
        'transaction' => 'Transaction',
        'dashboard' => 'Dashboard',
        'invoice' => 'Invoice',
    ],
];
