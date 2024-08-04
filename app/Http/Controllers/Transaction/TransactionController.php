<?php

namespace App\Http\Controllers\Transaction;

use App\Constants\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\FilterTransactionsRequest;
use App\Http\Resources\Transaction\TransactionListResource;
use App\Models\Payment;
use Inertia\Inertia;
use Inertia\Response;

class TransactionController extends Controller
{

    public function index(FilterTransactionsRequest $request): Response
    {
        $micrositeName = $request->input('microsite');
        $status = $request->input('status');
        $reference = $request->input('reference');
        $document = $request->input('document');

        $payments = Payment::query()
            ->with('microsite:id,name')
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
            ->when($micrositeName, function ($query, $micrositeSlug) {
                return $query->whereHas('microsite', function ($query) use ($micrositeSlug) {
                    return $query->where('name', $micrositeSlug);
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($reference, function ($query, $reference) {
                return $query->where('reference', 'like', '%' . $reference . '%');
            })
            ->when($document, function ($query, $document) {
                return $query->where('document', 'like', '%' . $document . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->onEachSide(1)
            ->withQueryString();

        $transactions = TransactionListResource::collection($payments);

        return Inertia::render('Transactions/Index', [
            'transactions' => $transactions,
            'paymentStatuses' => PaymentStatus::toSelectArray(),
            'filters' => [
                'microsite' => $micrositeName,
                'status' => $status,
                'reference' => $reference,
                'document' => $document,
            ],
        ]);
    }

}
