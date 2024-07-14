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
        ];
    }
}
