<?php

namespace App\Constants;

enum MicrositeField: string
{
    case BUYER_DOCUMENT = 'buyer_document';
    case BUYER_DOCUMENT_TYPE = 'buyer_document_type';
    case BUYER_NAME = 'buyer_name';
    case BUYER_EMAIL = 'buyer_email';
    case PAYMENT_DESCRIPTION = 'payment_description';
    case AMOUNT = 'amount';
    case REFERENCE = 'reference';
    case DOCUMENT_NUMBER = 'document_number';

    public function type(): string
    {
        return match($this) {
            self::BUYER_DOCUMENT,
            self::BUYER_DOCUMENT_TYPE,
            self::BUYER_NAME,
            self::PAYMENT_DESCRIPTION,
            self::REFERENCE,
            self::DOCUMENT_NUMBER => 'text',
            self::BUYER_EMAIL => 'email',
            self::AMOUNT => 'number',
        };
    }

    public function defaultValidationRules(): array
    {
        return match($this) {
            self::BUYER_DOCUMENT, self::BUYER_DOCUMENT_TYPE => ['required', 'string', 'max:20'],
            self::BUYER_NAME => ['required', 'string', 'max:100'],
            self::BUYER_EMAIL => ['required', 'email', 'max:100'],
            self::PAYMENT_DESCRIPTION => ['required', 'string', 'max:255'],
            self::REFERENCE, self::DOCUMENT_NUMBER => ['nullable', 'string', 'max:50'],
            self::AMOUNT => ['required', 'numeric', 'min:0'],
        };
    }

    public static function defaultFieldsForMicrositeType(MicrositeType $type): array
    {
        return match($type) {
            MicrositeType::BASIC => [
                self::BUYER_DOCUMENT,
                self::BUYER_DOCUMENT_TYPE,
                self::BUYER_NAME,
                self::BUYER_EMAIL,
                self::PAYMENT_DESCRIPTION,
                self::AMOUNT,
            ],
            MicrositeType::INVOICE => [
                self::REFERENCE,
                self::DOCUMENT_NUMBER,
            ],
            default => []
        };
    }
}
