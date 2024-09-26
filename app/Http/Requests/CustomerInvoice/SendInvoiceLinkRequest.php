<?php

namespace App\Http\Requests\CustomerInvoice;

use Illuminate\Foundation\Http\FormRequest;

class SendInvoiceLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:invoices,email'],
            'document_number' => ['required', 'exists:invoices,document_number'],
        ];
    }
}
