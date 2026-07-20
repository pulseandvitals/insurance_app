<?php

namespace App\Http\Controllers;

use App\Http\Requests\Policyholder\PolicyholderRequest;
use App\Http\Requests\Policyholder\StorePolicyholderRequest;
use App\Models\MotorQuote;
use App\Models\Policyholder;
use Illuminate\Http\RedirectResponse;

class PolicyholderController extends Controller
{
    public function store(StorePolicyholderRequest $request, MotorQuote $motorQuote): RedirectResponse
    {
        $type = $request->validated('type');

        Policyholder::create([
            'motor_quote_id' => $motorQuote->id,
            'type' => $type,
            'use_as_address' => $type !== 'lender' && $motorQuote->policyholders()->count() === 0,
            ...$request->toPolicyholderAttributes(),
        ]);

        return back()->with('success', 'Policyholder added.');
    }

    public function destroy(PolicyholderRequest $request, MotorQuote $motorQuote, Policyholder $policyholder): RedirectResponse
    {
        $policyholder->delete();

        return back()->with('success', 'Policyholder removed.');
    }

    public function useAddress(PolicyholderRequest $request, MotorQuote $motorQuote, Policyholder $policyholder): RedirectResponse
    {
        $motorQuote->policyholders()->update(['use_as_address' => false]);
        $policyholder->update(['use_as_address' => true]);

        return back();
    }
}
