<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class ImportInvoicesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'invoices' => 'required|file|mimes:csv,txt|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'invoices.required' => __('invoices.import.file_required'),
            'invoices.file' => __('invoices.import.file_must_be_a_file'),
            'invoices.mimes' => __('invoices.import.file_must_be_csv'),
            'invoices.max' => __('invoices.import.file_too_large'),
        ];
    }
}
