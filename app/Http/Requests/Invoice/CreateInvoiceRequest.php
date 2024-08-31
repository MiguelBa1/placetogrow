<?php

namespace App\Http\Requests\Invoice;

use App\Models\Microsite;
use Illuminate\Foundation\Http\FormRequest;

class CreateInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(?Microsite $microsite): array
    {
        $micrositeId = $microsite ? $microsite->id : $this->route('microsite')->id;

        return [
            'reference' => [
                'required',
                'string',
                'max:100',
                'unique:invoices,reference,NULL,id,microsite_id,' . $micrositeId,
            ],
            'document_type' => ['required', 'string', 'max:20'],
            'document_number' => ['required', 'regex:/^\d+$/', 'max:20'],
            'name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'phone' => ['required', 'min:10', 'regex:/^(\+?\d{1,3}[- ]?)?\d{10}$/'],
            'amount' => ['required', 'numeric', 'min:0'],
        ];
    }

}
