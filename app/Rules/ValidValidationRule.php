<?php

namespace App\Rules;

use Exception;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class ValidValidationRule implements ValidationRule
{
    public function validate($attribute, $value, $fail): void
    {
        $testData = ['test_field' => 'test_value'];

        try {
            $validator = Validator::make($testData, [
                'test_field' => $value
            ]);

            if ($validator->fails()) {
                $fail(__('validation.custom.validation_rules.invalid'));
            }
        } catch (Exception) {
            $fail(__('validation.custom.validation_rules.invalid'));
        }
    }
}
