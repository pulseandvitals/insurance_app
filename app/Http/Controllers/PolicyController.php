<?php

namespace App\Http\Controllers;

use App\Http\Requests\Policy\BatchCocExportRequest;
use App\Http\Requests\Policy\ExtractionsExportRequest;
use App\Http\Requests\Policy\PoliciesIndexRequest;
use App\Http\Requests\Policy\PolicyRequest;
use App\Http\Requests\Policy\RenewalSearchRequest;
use App\Http\Resources\PolicyResource;
use App\Http\Resources\WalletResource;
use App\Models\Policy;
use App\Support\PrintLogo;
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
    public function index(PoliciesIndexRequest $request): Response
    {
        $producer = $request->user()->producer;
        $filters = $request->validated();

        $policies = $producer->policies()
            ->with('motorQuote.policyholders')
            ->where('issued_at', '>=', now()->subDays(30))
            ->when($filters['assured_name'] ?? null, fn ($q, $v) => $q->whereHas(
                'motorQuote.policyholders',
                fn ($qq) => $qq->where('name', 'like', "%{$v}%"),
            ))
            ->when($filters['online_policy_no'] ?? null, fn ($q, $v) => $q->where('online_policy_no', 'like', "%{$v}%"))
            ->when($filters['chassis_no'] ?? null, fn ($q, $v) => $q->whereHas('motorQuote', fn ($qq) => $qq->where('chassis_no', 'like', "%{$v}%")))
            ->when($filters['motor_no'] ?? null, fn ($q, $v) => $q->whereHas('motorQuote', fn ($qq) => $qq->where('motor_no', 'like', "%{$v}%")))
            ->when(($filters['cov_status'] ?? null) === 'With COV', fn ($q) => $q->where('has_cov', true))
            ->when(($filters['cov_status'] ?? null) === 'No COV', fn ($q) => $q->where('has_cov', false))
            ->when(($filters['direct_status'] ?? null) === 'Yes', fn ($q) => $q->where('is_direct', true))
            ->when(($filters['direct_status'] ?? null) === 'No', fn ($q) => $q->where('is_direct', false))
            ->latest()
            ->get();

        return Inertia::render('MotorInsurance/PoliciesSold', [
            'policies' => PolicyResource::collection($policies),
            'filters' => $filters,
        ]);
    }

    public function show(PolicyRequest $request, Policy $policy): Response
    {
        return Inertia::render('Policies/Show', [
            'policy' => new PolicyResource($policy->load(['motorQuote.policyholders', 'producer'])),
        ]);
    }

    public function renewal(Request $request): Response
    {
        return Inertia::render('MotorInsurance/Renewal', [
            'wallet' => new WalletResource($request->user()->producer->wallet),
        ]);
    }

    public function renewalSearch(RenewalSearchRequest $request): RedirectResponse
    {
        $data = $request->validated();

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

    public function extractionsExport(ExtractionsExportRequest $request): StreamedResponse
    {
        $data = $request->validated();

        $policies = $request->user()->producer->policies()
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

    public function batchCocExport(BatchCocExportRequest $request): HttpResponse|RedirectResponse
    {
        $data = $request->validated();

        $policies = $request->user()->producer->policies()
            ->with('motorQuote.policyholders', 'producer')
            ->whereBetween('issued_at', [$data['date_from'], $data['date_to']])
            ->get();

        if ($policies->isEmpty()) {
            return back()->with('error', 'No policies were found within the selected date range.');
        }

        $pdf = Pdf::loadView('policies.batch-coc', ['policies' => $policies, 'logo' => PrintLogo::dataUri()]);

        return $pdf->download('batch-coc-'.now()->format('Ymd-His').'.pdf');
    }

    public function download(PolicyRequest $request, Policy $policy): HttpResponse
    {
        $policy->load('motorQuote.policyholders', 'producer');

        $pdf = Pdf::loadView('policies.print', ['policy' => $policy, 'mode' => 'coc', 'logo' => PrintLogo::dataUri()]);

        return $pdf->download("COC-{$policy->online_policy_no}.pdf");
    }

    public function print(PolicyRequest $request, Policy $policy, string $mode)
    {
        $policy->load('motorQuote.policyholders', 'producer');

        return view('policies.print', ['policy' => $policy, 'mode' => $mode, 'logo' => PrintLogo::dataUri()]);
    }
}
