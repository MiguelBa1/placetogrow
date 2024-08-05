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
            $options = MicrositeField::optionsForField($field);

            $micrositeField = new MicrositeFieldModel([
                'name' => $field->value,
                'label' => $field->value,
                'type' => $field->type(),
                'validation_rules' => implode('|', $field->defaultValidationRules()),
                'modifiable' => false,
                'options' => $options ? array_column($options, 'value') : null,
            ]);

            $microsite->fields()->save($micrositeField);

            $this->addTranslations($micrositeField, $field->value);
        }
    }

    protected function addTranslations(MicrositeFieldModel $micrositeField, string $fieldKey): void
    {
        $translations = [
            'en' => __('microsite_fields.' . $fieldKey, [], 'en'),
            'es' => __('microsite_fields.' . $fieldKey, [], 'es'),
        ];

        foreach ($translations as $locale => $label) {
            $fieldTranslation = new FieldTranslation([
                'locale' => $locale,
                'label' => $label,
            ]);

            $micrositeField->translations()->save($fieldTranslation);
        }
    }
}
