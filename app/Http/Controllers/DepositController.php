<?php

namespace App\Http\Controllers;

use App\Http\Requests\Deposit\DepositOwnershipRequest;
use App\Http\Requests\Deposit\UploadProofRequest;
use App\Http\Resources\DepositResource;
use App\Models\Deposit;
use App\Models\WalletTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

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

    public function pay(DepositOwnershipRequest $request, Deposit $deposit): RedirectResponse
    {
        abort_unless($deposit->needsPayment(), 422);

        $deposit->update([
            'status' => Deposit::STATUS_PAID_ONLINE_CONFIRMED,
            'approved_by' => 'System Auto-Approval',
            'approved_date' => now(),
        ]);

        $this->creditWallet($deposit);

        return back()->with('success', "Payment for deposit {$deposit->ref_no} confirmed. Your e-wallet has been credited.");
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
