<?php

namespace App\Http\Controllers\CustomerInvoice;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class CustomerInvoiceController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('CustomerInvoices/Index');
    }
}
