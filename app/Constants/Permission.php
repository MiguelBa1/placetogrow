<?php

namespace App\Constants;

enum Permission: string
{
    case VIEW_ANY_MICROSITE = 'view_any_microsite';
    case VIEW_MICROSITE = 'view_microsite';
    case CREATE_MICROSITE = 'create_microsite';
    case UPDATE_MICROSITE = 'update_microsite';
    case DELETE_MICROSITE = 'delete_microsite';
    case RESTORE_MICROSITE = 'restore_microsite';

    case VIEW_ANY_PLAN = 'view_any_plan';
    case VIEW_PLAN = 'view_plan';
    case CREATE_PLAN = 'create_plan';
    case UPDATE_PLAN = 'update_plan';
    case DELETE_PLAN = 'delete_plan';
    case RESTORE_PLAN = 'restore_plan';

    case VIEW_ANY_USER = 'view_any_user';
    case UPDATE_USER_ROLE = 'update_user_role';
    case MANAGE_ROLES = 'manage_roles';
    case VIEW_DASHBOARD = 'view_dashboard';

    case VIEW_ANY_INVOICE = 'view_any_invoice';
    case CREATE_INVOICE = 'create_invoice';
    case IMPORT_INVOICE = 'import_invoice';

    case VIEW_ANY_TRANSACTION = 'view_any_transaction';
    case VIEW_TRANSACTION = 'view_transaction';

    public static function grouped(): array
    {
        return [
            'microsite' => [
                self::VIEW_ANY_MICROSITE,
                self::VIEW_MICROSITE,
                self::CREATE_MICROSITE,
                self::UPDATE_MICROSITE,
                self::DELETE_MICROSITE,
                self::RESTORE_MICROSITE,
            ],
            'invoice' => [
                self::VIEW_ANY_INVOICE,
                self::CREATE_INVOICE,
                self::IMPORT_INVOICE,
            ],
            'plan' => [
                self::VIEW_ANY_PLAN,
                self::VIEW_PLAN,
                self::CREATE_PLAN,
                self::UPDATE_PLAN,
                self::DELETE_PLAN,
                self::RESTORE_PLAN,
            ],
            'transaction' => [
                self::VIEW_ANY_TRANSACTION,
                self::VIEW_TRANSACTION,
            ],
            'user' => [
                self::VIEW_ANY_USER,
                self::UPDATE_USER_ROLE,
            ],
            'role' => [
                self::MANAGE_ROLES,
            ],
            'dashboard' => [
                self::VIEW_DASHBOARD,
            ],
        ];
    }
}
