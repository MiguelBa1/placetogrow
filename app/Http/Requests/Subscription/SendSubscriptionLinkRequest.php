<?php

namespace App\Http\Requests\Subscription;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;

class SendSubscriptionLinkRequest extends FormRequest
{
    public ?Customer $customer = null;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'document_number' => 'required|string',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $this->customer = Customer::where('email', $this->get('email'))
                ->where('document_number', $this->get('document_number'))
                ->first();

            if (!$this->customer) {
                $validator->errors()->add('invalid', __('customer_subscriptions.invalid_customer'));
            }
        });
    }
}
