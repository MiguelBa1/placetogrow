<?php

namespace App\Constants;

enum PolicyName: string
{
    case VIEW_ANY = 'viewAny';
    case VIEW = 'view';
    case CREATE = 'create';
    case UPDATE = 'update';
    case DELETE = 'delete';
    case RESTORE = 'restore';
    case FORCE_DELETE = 'forceDelete';
    case MANAGE = 'manage';
    case UPDATE_ROLE = 'updateRole';
    case IMPORT = 'import';
}
