<?php

use App\Constants\Permission;

return [
    Permission::VIEW_ANY_MICROSITE->value => 'Ver cualquier micrositio',
    Permission::VIEW_MICROSITE->value => 'Ver micrositio',
    Permission::CREATE_MICROSITE->value => 'Crear micrositio',
    Permission::UPDATE_MICROSITE->value => 'Actualizar micrositio',
    Permission::DELETE_MICROSITE->value => 'Eliminar micrositio',
    Permission::RESTORE_MICROSITE->value => 'Restaurar micrositio',

    Permission::VIEW_ANY_PLAN->value => 'Ver cualquier plan',
    Permission::VIEW_PLAN->value => 'Ver plan',
    Permission::CREATE_PLAN->value => 'Crear plan',
    Permission::UPDATE_PLAN->value => 'Actualizar plan',
    Permission::DELETE_PLAN->value => 'Eliminar plan',
    Permission::RESTORE_PLAN->value => 'Restaurar plan',

    Permission::VIEW_ANY_USER->value => 'Ver cualquier usuario',
    Permission::UPDATE_USER_ROLE->value => 'Actualizar rol de usuario',

    Permission::MANAGE_ROLES->value => 'Administrar roles',
    Permission::VIEW_DASHBOARD->value => 'Ver panel de control',

    Permission::VIEW_ANY_INVOICE->value => 'Ver cualquier factura',
    Permission::CREATE_INVOICE->value => 'Crear factura',
    Permission::IMPORT_INVOICE->value => 'Importar factura',

    Permission::VIEW_ANY_TRANSACTION->value => 'Ver cualquier transacción',
    Permission::VIEW_TRANSACTION->value => 'Ver transacción',

    'group' => [
        'microsite' => 'Micrositio',
        'user' => 'Usuario',
        'role' => 'Rol',
        'plan' => 'Plan',
        'transaction' => 'Transacción',
        'dashboard' => 'Panel de control',
        'invoice' => 'Factura',
    ],
];
