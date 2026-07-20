<?php

namespace App\Http\Controllers;

use App\Http\Requests\Producer\DashboardFilterRequest;
use App\Http\Requests\Producer\ProducerRequest;
use App\Http\Requests\Producer\UpdateProducerRequest;
use App\Http\Resources\ProducerResource;
use App\Models\MotorQuote;
use App\Models\Producer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class ProducerController extends Controller
{
    public function dashboard(DashboardFilterRequest $request, Producer $producer): Response
    {
        $dateFrom = $request->date('date_from');
        $dateTo = $request->date('date_to');

        $quotes = MotorQuote::query()
            ->where('producer_id', $producer->id)
            ->when($dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('created_at', '<=', $dateTo));

        $quotationsCount = (clone $quotes)->count();
        $quotationsTotal = (clone $quotes)->sum('total_premium');

        $policies = (clone $quotes)->where('status', MotorQuote::STATUS_POLICY);
        $policiesCount = (clone $policies)->count();
        $policiesTotal = (clone $policies)->sum('total_premium');

        return Inertia::render('Producers/Dashboard', [
            'producer' => new ProducerResource($producer),
            'filters' => $request->only(['date_from', 'date_to']),
            'stats' => [
                'quotations_count' => $quotationsCount,
                'quotations_total' => (float) $quotationsTotal,
                'policies_count' => $policiesCount,
                'policies_total' => (float) $policiesTotal,
            ],
        ]);
    }

    public function edit(ProducerRequest $request, Producer $producer): Response
    {
        return Inertia::render('Producers/Account', [
            'producer' => new ProducerResource($producer),
        ]);
    }

    public function update(UpdateProducerRequest $request, Producer $producer): RedirectResponse
    {
        if ($password = $request->validated('password')) {
            $producer->user->update(['password' => Hash::make($password)]);
        }

        return back()->with('success', 'Your account changes have been saved.');
    }
}
