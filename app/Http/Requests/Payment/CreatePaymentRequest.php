<?php

namespace App\Http\Requests\Payment;

use App\Constants\FieldType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $fields = $this->route('microsite')->fields;

        $rules = [];

        foreach ($fields as $field) {
            $fieldRules = explode('|', $field->validation_rules);

            if ($field->type === FieldType::SELECT->value && !empty($field->options)) {
                $fieldRules[] = Rule::in($field->options);
            }

            $rules[$field->name] = $fieldRules;
        }

        return $rules;
    }
}
