<?php

namespace App\Http\Controllers;

use App\Http\Requests\Wallet\DepositRequest;
use App\Http\Requests\Wallet\WalletFilterRequest;
use App\Http\Resources\WalletResource;
use App\Http\Resources\WalletTransactionResource;
use App\Models\Deposit;
use App\Models\WalletTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class WalletController extends Controller
{
    public function show(WalletFilterRequest $request): Response
    {
        $producer = $request->user()->producer;
        $wallet = $producer->wallet;

        $dateFrom = $request->date('date_from');
        $dateTo = $request->date('date_to');

        $transactions = $wallet->transactions()
            ->when($dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->latest()
            ->paginate(8)
            ->withQueryString();

        $transactions->through(fn (WalletTransaction $transaction) => new WalletTransactionResource($transaction));

        return Inertia::render('Producers/Wallet', [
            'wallet' => new WalletResource($wallet),
            'transactions' => $transactions,
            'walletHandle' => $producer->wallet_handle,
            'filters' => $request->only(['date_from', 'date_to']),
        ]);
    }

    public function deposit(DepositRequest $request): RedirectResponse
    {
        $producer = $request->user()->producer;

        $depositTypeMap = [
            'Pay with Credit/Debit Card' => 'Credit/Debit Card',
            'GCash' => 'GCash',
            'GrabPay' => 'Grab Pay',
            'Pay Through Bills Payment' => 'Payment via Bills Payment',
        ];

        $depositType = $depositTypeMap[$request->validated('payment_method')];
        $status = $depositType === 'Payment via Bills Payment' ? Deposit::STATUS_AWAITING_PROOF : Deposit::STATUS_INITIATED_ONLINE;

        Deposit::create([
            'producer_id' => $producer->id,
            'ref_no' => 'DEP-'.strtoupper(Str::random(8)),
            'amount' => $request->validated('amount'),
            'status' => $status,
            'deposit_type' => $depositType,
        ]);

        $message = $status === Deposit::STATUS_AWAITING_PROOF
            ? 'Deposit request created. Please upload your proof of payment on the Deposits page to complete it.'
            : 'Deposit request created. Please complete payment on the Deposits page.';

        return redirect()->route('topups.index')->with('success', $message);
    }
}
