<?php

namespace App\Actions\MicrositeField;

use App\Constants\MicrositeField;
use App\Constants\MicrositeType;
use App\Models\Microsite;
use App\Models\MicrositeField as MicrositeFieldModel;

class AttachMicrositeFieldsAction
{
    public function execute(Microsite $microsite): void
    {
        $micrositeType = MicrositeType::from($microsite->type->value);

        $defaultFields = MicrositeField::defaultFieldsForMicrositeType($micrositeType);
        foreach ($defaultFields as $field) {
            $micrositeField = MicrositeFieldModel::firstOrCreate(
                ['name' => $field->value],
                [
                    'label' => $field->value,
                    'type' => $field->type(),
                    'is_required' => true,
                    'validation_rules' => implode('|', $field->defaultValidationRules())
                ]
            );
            $microsite->fields()->attach($micrositeField->id, ['is_required' => true]);
        }
    }
}
