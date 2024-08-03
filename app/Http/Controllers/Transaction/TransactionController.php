<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Http\Resources\Transaction\TransactionListResource;
use Inertia\Inertia;
use Inertia\Response;

class TransactionController extends Controller
{

    public function index(): Response
    {
        $payments = Payment::query()
            ->select(
                'id',
                'reference',
                'description',
                'status',
                'payment_date',
                'currency',
                'amount',
                'microsite_id'
            )
            ->with('microsite:id,name')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->onEachSide(1)
            ->withQueryString();

        $transactions = TransactionListResource::collection($payments);

        return Inertia::render('Transactions/Index', [
            'transactions' => $transactions,
        ]);
    }

}
