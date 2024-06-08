<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|regex:/[a-zA-Z ]/',
            'lastName' => 'required|string|regex:/[a-zA-Z ]/',
            'email' => 'required|email',
            'documentType' => 'required|in:CC,PPN',
            'documentNumber' => 'required|integer',
            'phone' => 'required|integer',
            'currency' => 'required|in:COP,USD',
            'amount' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'El nombre debe ser una cadena de texto',
            'lastName.regex' => 'El apellido debe ser una cadena de texto',
            'email.email' => 'El correo electrónico no es válido',
            'documentType.in' => 'El tipo de documento no es válido',
            'documentNumber.integer' => 'El número de documento no es válido',
            'phone.integer' => 'El teléfono no es válido',
            'currency.in' => 'La moneda no es válida',
            'amount.integer' => 'El valor a pagar no es válido',
        ];
    }
}
