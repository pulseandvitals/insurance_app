<?php

namespace App\Http\Controllers;

use App\Http\Requests\Deposit\DepositOwnershipRequest;
use App\Http\Requests\Deposit\UploadProofRequest;
use App\Http\Resources\DepositResource;
use App\Models\Deposit;
use App\Models\WalletTransaction;
use App\Services\PaymongoClient;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class DepositController extends Controller
{
    public function index(Request $request): Response
    {
        $producer = $request->user()->producer;

        $deposits = $producer->deposits()->latest()->paginate(8)->withQueryString();
        $deposits->through(fn (Deposit $deposit) => new DepositResource($deposit));

        return Inertia::render('Deposits/Index', [
            'deposits' => $deposits,
            'walletHandle' => $producer->wallet_handle,
        ]);
    }

    public function pay(DepositOwnershipRequest $request, Deposit $deposit, PaymongoClient $paymongo): HttpResponse
    {
        abort_unless($deposit->needsPayment(), 422);

        try {
            $session = $paymongo->createCheckoutSession($deposit);
        } catch (RequestException $e) {
            report($e);

            return back()->with('error', 'Could not start PayMongo checkout. Please try again in a moment.');
        }

        $deposit->update([
            'paymongo_checkout_session_id' => $session['id'],
            'paymongo_payment_intent_id' => $session['attributes']['payment_intent']['id'] ?? null,
        ]);

        return Inertia::location($session['attributes']['checkout_url']);
    }

    public function callback(DepositOwnershipRequest $request, Deposit $deposit, PaymongoClient $paymongo): RedirectResponse
    {
        if (! $deposit->needsPayment()) {
            return redirect()->route('topups.index')->with('success', "Deposit {$deposit->ref_no} is already settled.");
        }

        if (! $deposit->paymongo_checkout_session_id) {
            return redirect()->route('topups.index')->with('error', 'No PayMongo checkout was found for this deposit. Please click Pay to start a new one.');
        }

        $session = $paymongo->retrieveCheckoutSession($deposit->paymongo_checkout_session_id);

        if (! $this->wasPaid($session)) {
            return redirect()->route('topups.index')->with('error', "Payment for deposit {$deposit->ref_no} was not completed. You can click Pay to try again.");
        }

        $deposit->update([
            'status' => Deposit::STATUS_PAID_ONLINE_CONFIRMED,
            'approved_by' => 'PayMongo',
            'approved_date' => now(),
        ]);

        $this->creditWallet($deposit);

        return redirect()->route('topups.index')->with('success', "Payment for deposit {$deposit->ref_no} confirmed via PayMongo. Your e-wallet has been credited.");
    }

    public function upload(UploadProofRequest $request, Deposit $deposit): RedirectResponse
    {
        abort_unless($deposit->needsUpload(), 422);

        $path = $request->file('proof')->store('deposit-proofs', 'local');

        $deposit->update([
            'status' => Deposit::STATUS_BANK_PAYMENT_CONFIRMED,
            'approved_by' => 'System Auto-Verification',
            'approved_date' => now(),
            'proof_path' => $path,
        ]);

        $this->creditWallet($deposit);

        return back()->with('success', "Proof of payment uploaded and verified for deposit {$deposit->ref_no}. Your e-wallet has been credited.");
    }

    /**
     * A Checkout Session is authoritatively "paid" once its Payment Intent
     * has succeeded, or (as a fallback across API versions) it has at least
     * one Payment resource recorded against it.
     */
    private function wasPaid(array $session): bool
    {
        $attributes = $session['attributes'] ?? [];

        if (($attributes['payment_intent']['attributes']['status'] ?? null) === 'succeeded') {
            return true;
        }

        foreach ($attributes['payments'] ?? [] as $payment) {
            if (($payment['attributes']['status'] ?? null) === 'paid') {
                return true;
            }
        }

        return false;
    }

    private function creditWallet(Deposit $deposit): void
    {
        $wallet = $deposit->producer->wallet;

        $wallet->increment('balance', $deposit->amount);
        $wallet->increment('accumulated_deposit', $deposit->amount);
        $wallet->increment('total_received', $deposit->amount);

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'from_handle' => 'SICI Treasury',
            'to_handle' => $deposit->producer->wallet_handle,
            'transaction_type' => WalletTransaction::TYPE_DEPOSIT,
            'reference_label' => 'Deposit UUID: '.$deposit->ref_no,
            'ref_no' => $deposit->ref_no,
            'debit' => 0,
            'credit' => $deposit->amount,
        ]);
    }
}
