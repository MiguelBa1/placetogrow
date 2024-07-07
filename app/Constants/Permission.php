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

    case MANAGE_PERMISSIONS = 'manage_permissions';
}
