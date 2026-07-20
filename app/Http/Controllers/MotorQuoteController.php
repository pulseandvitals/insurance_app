<?php

namespace App\Http\Controllers;

use App\Models\MotorQuote;
use App\Models\Policy;
use App\Models\WalletTransaction;
use App\Services\CtplPremiumCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class MotorQuoteController extends Controller
{
    /** Vehicle class + coverage-period combinations with no prepaid setup on file (demo constraint). */
    private const NO_PREPAID_SETUP = [
        ['LTO Public Utility Bus', 3],
    ];

    public function __construct(private readonly CtplPremiumCalculator $calculator) {}

    public function index(Request $request): Response
    {
        $producer = $request->user()->producer;

        $quotes = $producer->motorQuotes()
            ->when($request->filled('plate_no'), fn ($q) => $q->where('plate_no', 'like', '%'.$request->input('plate_no').'%'))
            ->when($request->filled('quote_ref'), fn ($q) => $q->where('quote_ref', 'like', '%'.$request->input('quote_ref').'%'))
            ->latest()
            ->with('policy')
            ->get();

        return Inertia::render('MotorInsurance/Quotations', [
            'quotes' => $quotes,
            'filters' => $request->only(['transaction_type', 'plate_no', 'quote_ref']),
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('MotorInsurance/Retail/Step1', [
            'wallet' => $request->user()->producer->wallet,
            'prefill' => $request->session()->pull('ocr_prefill'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'lto_registration_type' => ['required', 'in:New,Renewal'],
            'vehicle_class' => ['required', 'in:Private,Motorcycles,Commercial-Trucks,LTO Tricycle,LTO Taxi,LTO Public Utility Jeepney,LTO Public Utility Bus'],
            'coverage_period' => ['required', 'in:1,3'],
            'surplus_vehicle' => ['required', 'boolean'],
            'year_model' => ['required', 'integer', 'min:1965', 'max:2027'],
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'variant' => ['required', 'string', 'max:100'],
            'plate_no' => ['required', 'string', 'max:20'],
        ]);

        $currentYear = (int) now()->format('Y');

        if ($data['lto_registration_type'] === 'New' && ! $data['surplus_vehicle'] && (int) $data['coverage_period'] === 1) {
            throw ValidationException::withMessages([
                'coverage_period' => "A 1-year CTPL term for 'New' registration type is only allowed for Surplus vehicles. Please select the 3-Year coverage period, or mark this as a Surplus Vehicle.",
            ]);
        }

        if ($data['year_model'] >= $currentYear && $data['lto_registration_type'] !== 'New') {
            throw ValidationException::withMessages([
                'lto_registration_type' => "Vehicles with year model {$data['year_model']} or later must use Registration Type: New.",
            ]);
        }

        foreach (self::NO_PREPAID_SETUP as [$class, $term]) {
            if ($data['vehicle_class'] === $class && (int) $data['coverage_period'] === $term) {
                throw ValidationException::withMessages([
                    'vehicle_class' => "No Prepaid setup for this producer for subline: {$class}, term: {$term} year/s.",
                ]);
            }
        }

        $premium = $this->calculator->calculate($data['vehicle_class'], (int) $data['coverage_period']);

        $quote = $request->user()->producer->motorQuotes()->create([
            'quote_ref' => 'QR-'.strtoupper(Str::random(10)),
            'status' => MotorQuote::STATUS_QUOTE,
            'lto_registration_type' => $data['lto_registration_type'],
            'vehicle_class' => $data['vehicle_class'],
            'coverage_period' => $data['coverage_period'],
            'surplus_vehicle' => $data['surplus_vehicle'],
            'year_model' => $data['year_model'],
            'brand' => $data['brand'],
            'model' => $data['model'],
            'variant' => $data['variant'],
            'plate_no' => $data['plate_no'],
            ...$premium,
        ]);

        return redirect()->route('motor-risks.show', $quote);
    }

    public function show(Request $request, MotorQuote $motorQuote): Response
    {
        $this->authorizeQuote($request, $motorQuote);

        return Inertia::render('MotorInsurance/Retail/Quote', [
            'quote' => $motorQuote,
            'producer' => $motorQuote->producer,
        ]);
    }

    public function proceed(Request $request, MotorQuote $motorQuote): RedirectResponse
    {
        $this->authorizeQuote($request, $motorQuote);

        if ($motorQuote->status === MotorQuote::STATUS_QUOTE) {
            $motorQuote->update(['status' => MotorQuote::STATUS_PRE_FLIGHT]);
        }

        return redirect()->route('motor-risks.pre-flight', $motorQuote);
    }

    public function preFlight(Request $request, MotorQuote $motorQuote): Response
    {
        $this->authorizeQuote($request, $motorQuote);

        return Inertia::render('MotorInsurance/Retail/PreFlight', [
            'quote' => $motorQuote->load('policyholders'),
            'wallet' => $motorQuote->producer->wallet,
        ]);
    }

    public function preFlightStore(Request $request, MotorQuote $motorQuote): RedirectResponse
    {
        $this->authorizeQuote($request, $motorQuote);

        $data = $request->validate([
            'chassis_no' => ['required', 'string', 'max:30'],
            'motor_no' => ['required', 'string', 'max:30'],
            'mv_file_no' => ['required', 'string', 'max:30'],
            'color' => ['required', 'string', 'max:60'],
            'other_info' => ['nullable', 'string', 'max:255'],
            'inception_date' => ['required', 'date'],
        ]);

        $inception = \Carbon\Carbon::parse($data['inception_date']);
        $expiry = $inception->copy()->addYears($motorQuote->coverage_period);

        $motorQuote->update([
            ...$data,
            'expiry_date' => $expiry->toDateString(),
        ]);

        return back()->with('success', 'Vehicle and inception date details saved.');
    }

    public function authenticateLto(Request $request, MotorQuote $motorQuote): RedirectResponse
    {
        $this->authorizeQuote($request, $motorQuote);

        if (! $motorQuote->color || ! $motorQuote->chassis_no || ! $motorQuote->mv_file_no) {
            return back()->with('error', 'Please complete the vehicle details (Color, Chassis/Serial No., MV File No.) before authenticating with LTO.');
        }

        if ($motorQuote->policyholders()->count() === 0) {
            return back()->with('error', 'Please add at least one Policyholder before authenticating with LTO.');
        }

        // Simulated LTO/Insurance Commission real-time validation.
        $mvFileValid = preg_match('/^[A-Z0-9]{15}$/', strtoupper($motorQuote->mv_file_no)) === 1;

        if (! $mvFileValid) {
            $motorQuote->update([
                'lto_status' => 'failed',
                'lto_message' => 'ERROR/S: The IC has indicated that this item is invalid: Submit COC Failed - The MV File No. does not exist.',
            ]);

            return back()->with('error', $motorQuote->lto_message);
        }

        $motorQuote->update([
            'lto_status' => 'verified',
            'lto_message' => 'COC successfully validated by the LTO/Insurance Commission web service.',
        ]);

        return redirect()->route('motor-risks.checkout', $motorQuote)->with('success', 'LTO authentication successful.');
    }

    public function checkout(Request $request, MotorQuote $motorQuote): Response|RedirectResponse
    {
        $this->authorizeQuote($request, $motorQuote);

        if ($motorQuote->lto_status !== 'verified') {
            return redirect()->route('motor-risks.pre-flight', $motorQuote)
                ->with('error', 'Please complete LTO authentication before proceeding to payment.');
        }

        return Inertia::render('MotorInsurance/Retail/Checkout', [
            'quote' => $motorQuote,
            'wallet' => $motorQuote->producer->wallet,
        ]);
    }

    public function checkoutStore(Request $request, MotorQuote $motorQuote): RedirectResponse
    {
        $this->authorizeQuote($request, $motorQuote);

        abort_unless($motorQuote->lto_status === 'verified', 422);

        $wallet = $motorQuote->producer->wallet;

        if ($wallet->balance < $motorQuote->total_premium) {
            return back()->with('error', 'Your e-wallet balance is insufficient to complete this purchase. Please reload your wallet.');
        }

        $policy = Policy::create([
            'motor_quote_id' => $motorQuote->id,
            'producer_id' => $motorQuote->producer_id,
            'online_policy_no' => 'OP-'.now()->format('Y').'-'.str_pad((string) $motorQuote->id, 6, '0', STR_PAD_LEFT),
            'genweb_code' => 'GW'.strtoupper(Str::random(8)),
            'coc_no' => 'COC-'.strtoupper(Str::random(10)),
            'authentication_no' => 'AUTH-'.strtoupper(Str::random(10)),
            'issued_at' => now(),
            'contract_from' => $motorQuote->inception_date,
            'contract_to' => $motorQuote->expiry_date,
        ]);

        $wallet->decrement('balance', $motorQuote->total_premium);
        $wallet->increment('total_net_remittance', $motorQuote->total_premium);

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'from_handle' => $motorQuote->producer->wallet_handle,
            'to_handle' => null,
            'transaction_type' => WalletTransaction::TYPE_PAYMENT_CTPL,
            'reference_label' => 'Policy ID: '.$policy->id,
            'ref_no' => $policy->online_policy_no,
            'debit' => $motorQuote->total_premium,
            'credit' => 0,
        ]);

        $motorQuote->update(['status' => MotorQuote::STATUS_POLICY]);

        return redirect()->route('policies.show', $policy)->with('success', 'Payment successful! Your CTPL policy has been issued.');
    }

    public function ocr(Request $request): Response
    {
        return Inertia::render('MotorInsurance/Ocr', [
            'wallet' => $request->user()->producer->wallet,
        ]);
    }

    public function ocrStore(Request $request): RedirectResponse
    {
        $request->validate([
            'document_type' => ['required', 'in:Certificate of Registration (CR)'],
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:8192'],
            'consent' => ['accepted'],
        ]);

        // Simulated OCR extraction result — a real integration would call a vision/OCR service here.
        $extracted = [
            'lto_registration_type' => 'Renewal',
            'vehicle_class' => 'Private',
            'year_model' => 2021,
            'brand' => 'Toyota',
            'model' => 'Fortuner',
            'variant' => '2.4 G 4x2 AT',
            'plate_no' => 'NCX'.random_int(1000, 9999),
        ];

        return redirect()->route('motor-risks.create')
            ->with('success', 'Document scanned successfully. Vehicle details were extracted and pre-filled below — please review before creating the quote.')
            ->with('ocr_prefill', $extracted);
    }

    private function authorizeQuote(Request $request, MotorQuote $motorQuote): void
    {
        abort_unless($request->user()->producer?->id === $motorQuote->producer_id, 403);
    }
}
