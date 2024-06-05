<?php

namespace App\Http\Controllers;

use App\PaymentServiceImp;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function index()
    {
        return Inertia::render('Site1/Index');
    }

    public function store(Request $request)
    {
        if (! preg_match("/[a-zA-Z ]/", $request->input('name'))) {
            throw ValidationException::withMessages(['name' => 'El nombre debe ser una cadena de texto']);
        } else if (! preg_match("/[a-zA-Z ]/", $request->input('name'))) {
            throw ValidationException::withMessages(['lastName' => 'El apellido debe ser una cadena de texto']);
        } else if (! filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)) {
            throw ValidationException::withMessages(['email' => 'El correo electrónico no es válido']);
        } else if (! in_array($request->input('documentType'), ['CC', 'PPN'])) {
            throw ValidationException::withMessages(['documentType' => 'El tipo de documento no es válido']);
        } else if (! filter_var($request->input('documentNumber'), FILTER_VALIDATE_INT)) {
            throw ValidationException::withMessages(['documentNumber' => 'El número de documento no es válido']);
        } else if (! filter_var($request->input('phone'), FILTER_VALIDATE_INT)) {
            throw ValidationException::withMessages(['documentNumber' => 'El teléfono no es válido']);
        } else if (! in_array($request->input('currency'), ['COP', 'USD'])) {
            throw ValidationException::withMessages(['currency' => 'La moneda no es válida']);
        } else if (! filter_var($request->input('amount'), FILTER_VALIDATE_INT)) {
            throw ValidationException::withMessages(['amount' => 'El valor a pagar no es válido']);
        }

        return (new PaymentServiceImp)->createPayment($request);
    }

    public function return($reference)
    {
        return (new PaymentServiceImp)->checkPayment($reference);
    }
}
