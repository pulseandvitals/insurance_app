<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GlobalPricingRequest;
use App\Models\GlobalPricing;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PricingController extends Controller
{
    public function edit(): Response
    {
        return Inertia::render('Admin/Pricing/Edit', [
            'pricing' => GlobalPricing::current(),
        ]);
    }

    public function update(GlobalPricingRequest $request): RedirectResponse
    {
        GlobalPricing::current()->update($request->validated());

        return back()->with('success', 'Global pricing updated.');
    }
}
