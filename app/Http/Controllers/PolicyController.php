<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response as ResponseFacade;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PolicyController extends Controller
{
    public function index(Request $request): Response
    {
        $producer = $request->user()->producer;

        $policies = $producer->policies()
            ->with('motorQuote.policyholders')
            ->where('issued_at', '>=', now()->subDays(30))
            ->when($request->filled('assured_name'), fn ($q) => $q->whereHas(
                'motorQuote.policyholders',
                fn ($qq) => $qq->where('name', 'like', '%'.$request->input('assured_name').'%'),
            ))
            ->when($request->filled('online_policy_no'), fn ($q) => $q->where('online_policy_no', 'like', '%'.$request->input('online_policy_no').'%'))
            ->when($request->filled('chassis_no'), fn ($q) => $q->whereHas('motorQuote', fn ($qq) => $qq->where('chassis_no', 'like', '%'.$request->input('chassis_no').'%')))
            ->when($request->filled('motor_no'), fn ($q) => $q->whereHas('motorQuote', fn ($qq) => $qq->where('motor_no', 'like', '%'.$request->input('motor_no').'%')))
            ->when($request->input('cov_status') === 'With COV', fn ($q) => $q->where('has_cov', true))
            ->when($request->input('cov_status') === 'No COV', fn ($q) => $q->where('has_cov', false))
            ->when($request->input('direct_status') === 'Yes', fn ($q) => $q->where('is_direct', true))
            ->when($request->input('direct_status') === 'No', fn ($q) => $q->where('is_direct', false))
            ->latest()
            ->get();

        return Inertia::render('MotorInsurance/PoliciesSold', [
            'policies' => $policies,
            'filters' => $request->only(['assured_name', 'online_policy_no', 'chassis_no', 'motor_no', 'cov_status', 'direct_status']),
        ]);
    }

    public function show(Request $request, Policy $policy): Response
    {
        $this->authorizePolicy($request, $policy);

        return Inertia::render('Policies/Show', [
            'policy' => $policy->load(['motorQuote.policyholders', 'producer']),
        ]);
    }

    public function renewal(Request $request): Response
    {
        return Inertia::render('MotorInsurance/Renewal', [
            'wallet' => $request->user()->producer->wallet,
        ]);
    }

    public function renewalSearch(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'online_policy_no' => ['nullable', 'string'],
            'chassis_no' => ['nullable', 'string'],
            'motor_no' => ['nullable', 'string'],
        ]);

        if (empty(array_filter($data))) {
            return back()->with('error', 'Please enter at least one of the fields to search.');
        }

        $policy = Policy::query()
            ->where('producer_id', $request->user()->producer->id)
            ->where(function ($outer) use ($data) {
                $outer->when($data['online_policy_no'] ?? null, fn ($q, $v) => $q->orWhere('online_policy_no', $v))
                    ->when($data['chassis_no'] ?? null, fn ($q, $v) => $q->orWhereHas('motorQuote', fn ($qq) => $qq->where('chassis_no', $v)))
                    ->when($data['motor_no'] ?? null, fn ($q, $v) => $q->orWhereHas('motorQuote', fn ($qq) => $qq->where('motor_no', $v)));
            })
            ->first();

        if (! $policy) {
            return back()->with('error', 'No matching policy was found. Please verify your Online Policy Number, Chassis Number, or Engine Number, or contact Customer Care.');
        }

        return redirect()->route('policies.show', $policy);
    }

    public function extractions(Request $request): Response
    {
        return Inertia::render('MotorInsurance/Extractions');
    }

    public function extractionsExport(Request $request): StreamedResponse
    {
        $data = $request->validate([
            'paid_unpaid' => ['nullable', 'in:All,PAID,UNPAID'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
        ]);

        $producer = $request->user()->producer;

        $policies = $producer->policies()
            ->with('motorQuote')
            ->when($data['date_from'] ?? null, fn ($q, $v) => $q->whereDate('issued_at', '>=', $v))
            ->when($data['date_to'] ?? null, fn ($q, $v) => $q->whereDate('issued_at', '<=', $v))
            ->when(($data['paid_unpaid'] ?? 'All') === 'UNPAID', fn ($q) => $q->whereRaw('1 = 0'))
            ->latest()
            ->get();

        $filename = 'policies-sold-extraction-'.now()->format('Ymd-His').'.csv';

        return ResponseFacade::streamDownload(function () use ($policies) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Online Policy No', 'Assured Name', 'Vehicle', 'Plate No', 'Chassis No', 'Motor No', 'Issued Date', 'Total Premium', 'Status']);

            foreach ($policies as $policy) {
                fputcsv($out, [
                    $policy->online_policy_no,
                    $policy->producer->full_name ?? '',
                    $policy->motorQuote->vehicle_title,
                    $policy->motorQuote->plate_no,
                    $policy->motorQuote->chassis_no,
                    $policy->motorQuote->motor_no,
                    $policy->issued_at->toDateTimeString(),
                    $policy->motorQuote->total_premium,
                    'PAID',
                ]);
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function batchCoc(Request $request): Response
    {
        return Inertia::render('MotorInsurance/BatchCoc');
    }

    public function batchCocExport(Request $request): HttpResponse|RedirectResponse
    {
        $data = $request->validate([
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date'],
        ]);

        $producer = $request->user()->producer;

        $policies = $producer->policies()
            ->with('motorQuote.policyholders', 'producer')
            ->whereBetween('issued_at', [$data['date_from'], $data['date_to']])
            ->get();

        if ($policies->isEmpty()) {
            return back()->with('error', 'No policies were found within the selected date range.');
        }

        $pdf = Pdf::loadView('policies.batch-coc', ['policies' => $policies]);

        return $pdf->download('batch-coc-'.now()->format('Ymd-His').'.pdf');
    }

    public function download(Request $request, Policy $policy): HttpResponse
    {
        $this->authorizePolicy($request, $policy);

        $policy->load('motorQuote.policyholders', 'producer');

        $pdf = Pdf::loadView('policies.print', ['policy' => $policy, 'mode' => 'coc']);

        return $pdf->download("COC-{$policy->online_policy_no}.pdf");
    }

    public function print(Request $request, Policy $policy, string $mode)
    {
        $this->authorizePolicy($request, $policy);

        abort_unless(in_array($mode, ['schedule', 'coc', 'cov', 'premium-statement', 'jacket']), 404);

        $policy->load('motorQuote.policyholders', 'producer');

        return view('policies.print', ['policy' => $policy, 'mode' => $mode]);
    }

    private function authorizePolicy(Request $request, Policy $policy): void
    {
        abort_unless($request->user()->producer?->id === $policy->producer_id, 403);
    }
}
