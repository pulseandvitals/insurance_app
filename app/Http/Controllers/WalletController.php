<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class WalletController extends Controller
{
    public function show(Request $request): Response
    {
        $producer = $request->user()->producer;
        $wallet = $producer->wallet;

        $dateFrom = $request->date('date_from');
        $dateTo = $request->date('date_to');

        $transactions = $wallet->transactions()
            ->when($dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->latest()
            ->get();

        return Inertia::render('Producers/Wallet', [
            'wallet' => $wallet,
            'transactions' => $transactions,
            'walletHandle' => $producer->wallet_handle,
            'filters' => [
                'date_from' => $request->input('date_from'),
                'date_to' => $request->input('date_to'),
            ],
        ]);
    }

    public function deposit(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:100'],
            'payment_method' => ['required', 'in:Pay with Credit/Debit Card,GCash,GrabPay,Pay Through Bills Payment'],
        ]);

        $producer = $request->user()->producer;

        $depositTypeMap = [
            'Pay with Credit/Debit Card' => 'Credit/Debit Card',
            'GCash' => 'GCash',
            'GrabPay' => 'Grab Pay',
            'Pay Through Bills Payment' => 'Payment via Bills Payment',
        ];

        $depositType = $depositTypeMap[$data['payment_method']];
        $status = $depositType === 'Payment via Bills Payment' ? Deposit::STATUS_AWAITING_PROOF : Deposit::STATUS_INITIATED_ONLINE;

        Deposit::create([
            'producer_id' => $producer->id,
            'ref_no' => 'DEP-'.strtoupper(Str::random(8)),
            'amount' => $data['amount'],
            'status' => $status,
            'deposit_type' => $depositType,
        ]);

        $message = $status === Deposit::STATUS_AWAITING_PROOF
            ? 'Deposit request created. Please upload your proof of payment on the Deposits page to complete it.'
            : 'Deposit request created. Please complete payment on the Deposits page.';

        return redirect()->route('topups.index')->with('success', $message);
    }
}
