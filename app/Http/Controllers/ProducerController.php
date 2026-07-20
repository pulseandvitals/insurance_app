<?php

namespace App\Http\Controllers;

use App\Models\MotorQuote;
use App\Models\Producer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ProducerController extends Controller
{
    public function dashboard(Request $request, Producer $producer): Response
    {
        $this->authorizeProducer($request, $producer);

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
            'producer' => $producer,
            'filters' => [
                'date_from' => $request->input('date_from'),
                'date_to' => $request->input('date_to'),
            ],
            'stats' => [
                'quotations_count' => $quotationsCount,
                'quotations_total' => (float) $quotationsTotal,
                'policies_count' => $policiesCount,
                'policies_total' => (float) $policiesTotal,
            ],
        ]);
    }

    public function edit(Request $request, Producer $producer): Response
    {
        $this->authorizeProducer($request, $producer);

        return Inertia::render('Producers/Account', [
            'producer' => $producer,
        ]);
    }

    public function update(Request $request, Producer $producer): RedirectResponse
    {
        $this->authorizeProducer($request, $producer);

        $data = $request->validate([
            'password' => ['nullable', 'confirmed', 'min:8'],
            'consent' => ['accepted'],
        ]);

        if (! empty($data['password'])) {
            $producer->user->update(['password' => Hash::make($data['password'])]);
        }

        return back()->with('success', 'Your account changes have been saved.');
    }

    private function authorizeProducer(Request $request, Producer $producer): void
    {
        abort_unless($request->user()->producer?->id === $producer->id, 403);
    }
}
