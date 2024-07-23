<?php

namespace App\Actions\MicrositeField;

use App\Constants\MicrositeField;
use App\Constants\MicrositeType;
use App\Models\FieldTranslation;
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
                    'validation_rules' => implode('|', $field->defaultValidationRules())
                ]
            );

            $this->addTranslations($micrositeField, $field->value);

            $microsite->fields()->attach($micrositeField->id, ['modifiable' => false]);
        }
    }

    protected function addTranslations(MicrositeFieldModel $micrositeField, string $fieldKey): void
    {
        $translations = [
            'en' => __('microsite_fields.' . $fieldKey, [], 'en'),
            'es' => __('microsite_fields.' . $fieldKey, [], 'es'),
        ];

        foreach ($translations as $locale => $label) {
            FieldTranslation::firstOrCreate(
                ['field_id' => $micrositeField->id, 'locale' => $locale],
                ['label' => $label]
            );
        }
    }

}
