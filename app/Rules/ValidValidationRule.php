<?php

namespace App\Rules;

use BadMethodCallException;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ValidValidationRule implements ValidationRule
{
    public function validate($attribute, $value, $fail): void
    {
        $testData = ['test_field' => 'test_value'];

        try {
            $validator = Validator::make($testData, [
                'test_field' => $value
            ]);

            $validator->validate();
        } catch (BadMethodCallException) {
            $fail(__('validation.custom.validation_rules.invalid'));
        } catch (ValidationException) {
            return;
        }
    }
}
