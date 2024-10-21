<?php

namespace App\Constants;

enum MicrositeField: string
{
    case DOCUMENT_NUMBER = 'document_number';
    case DOCUMENT_TYPE = 'document_type';
    case NAME = 'name';
    case LAST_NAME = 'last_name';
    case PHONE = 'phone';
    case EMAIL = 'email';
    case PAYMENT_DESCRIPTION = 'payment_description';
    case AMOUNT = 'amount';
    case REFERENCE = 'reference';

    public function type(): string
    {
        return match($this) {
            self::DOCUMENT_TYPE => 'select',
            self::NAME,
            self::LAST_NAME,
            self::PAYMENT_DESCRIPTION,
            self::REFERENCE => 'text',
            self::EMAIL => 'email',
            self::AMOUNT,
            self::DOCUMENT_NUMBER,
            self::PHONE => 'number',
        };
    }

    public function defaultValidationRules(): array
    {
        return match($this) {
            self::DOCUMENT_TYPE => ['required', 'string', 'max:20', 'in:' . implode(',', DocumentType::toArray())],
            self::DOCUMENT_NUMBER => ['required', 'string', 'max:20', 'alpha_num', 'min:5'],
            self::NAME, self::LAST_NAME => ['required', 'string', 'max:100'],
            self::EMAIL => ['required', 'email', 'max:100'],
            self::PAYMENT_DESCRIPTION => ['required', 'string', 'max:255'],
            self::REFERENCE => ['required', 'string', 'max:50'],
            self::PHONE => ['required', 'regex:/^(\+?\d{1,3}[- ]?)?\d{10}$/'],
            self::AMOUNT => ['required', 'numeric', 'min:1', 'regex:/^\d+(\.\d{1,2})?$/', 'max:999999.99'],
        };
    }

    public static function defaultFieldsForMicrositeType(MicrositeType $type): array
    {
        return match($type) {
            MicrositeType::BASIC => [
                self::DOCUMENT_NUMBER,
                self::DOCUMENT_TYPE,
                self::NAME,
                self::LAST_NAME,
                self::PHONE,
                self::EMAIL,
                self::PAYMENT_DESCRIPTION,
                self::AMOUNT,
            ],
            MicrositeType::INVOICE => [
                self::REFERENCE,
                self::DOCUMENT_NUMBER,
            ],
            MicrositeType::SUBSCRIPTION => [
                self::DOCUMENT_NUMBER,
                self::DOCUMENT_TYPE,
                self::NAME,
                self::LAST_NAME,
                self::PHONE,
                self::EMAIL,
            ],
            default => []
        };
    }

    public static function optionsForField(self $field): array
    {
        return match($field) {
            self::DOCUMENT_TYPE => DocumentType::toSelectArray(),
            default => []
        };
    }
}
