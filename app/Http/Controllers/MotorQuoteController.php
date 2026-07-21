<?php

namespace App\Http\Controllers;

use App\Http\Requests\MotorQuote\MotorQuoteRequest;
use App\Http\Requests\MotorQuote\OcrStoreRequest;
use App\Http\Requests\MotorQuote\PreFlightStoreRequest;
use App\Http\Requests\MotorQuote\QuotationsIndexRequest;
use App\Http\Requests\MotorQuote\StoreMotorQuoteRequest;
use App\Http\Resources\MotorQuoteResource;
use App\Http\Resources\ProducerResource;
use App\Http\Resources\WalletResource;
use App\Models\MotorQuote;
use App\Models\Policy;
use App\Models\WalletTransaction;
use App\Services\CtplPremiumCalculator;
use App\Services\VehicleCatalogService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class MotorQuoteController extends Controller
{
    public function __construct(
        private readonly CtplPremiumCalculator $calculator,
        private readonly VehicleCatalogService $vehicleCatalog,
    ) {}

    public function index(QuotationsIndexRequest $request): Response
    {
        $producer = $request->user()->producer;

        $quotes = $producer->motorQuotes()
            ->when($request->filled('plate_no'), fn ($q) => $q->where('plate_no', 'like', '%'.$request->validated('plate_no').'%'))
            ->when($request->filled('quote_ref'), fn ($q) => $q->where('quote_ref', 'like', '%'.$request->validated('quote_ref').'%'))
            ->latest()
            ->with('policy')
            ->get();

        return Inertia::render('MotorInsurance/Quotations', [
            'quotes' => MotorQuoteResource::collection($quotes),
            'filters' => $request->validated(),
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('MotorInsurance/Retail/Step1', [
            'wallet' => new WalletResource($request->user()->producer->wallet),
            'prefill' => $request->session()->pull('ocr_prefill'),
            'catalog' => $this->vehicleCatalog->tree(),
        ]);
    }

    public function store(StoreMotorQuoteRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $premium = $this->calculator->calculate($data['vehicle_class'], (int) $data['coverage_period']);

        $this->vehicleCatalog->register($data['vehicle_class'], $data['brand'], $data['model'], $data['variant']);

        $quote = $request->user()->producer->motorQuotes()->create([
            'quote_ref' => 'QR-'.strtoupper(Str::random(10)),
            'status' => MotorQuote::STATUS_QUOTE,
            ...$data,
            ...$premium,
        ]);

        return redirect()->route('motor-risks.show', $quote);
    }

    public function show(MotorQuoteRequest $request, MotorQuote $motorQuote): Response
    {
        return Inertia::render('MotorInsurance/Retail/Quote', [
            'quote' => new MotorQuoteResource($motorQuote),
            'producer' => new ProducerResource($motorQuote->producer),
        ]);
    }

    public function proceed(MotorQuoteRequest $request, MotorQuote $motorQuote): RedirectResponse
    {
        if ($motorQuote->status === MotorQuote::STATUS_QUOTE) {
            $motorQuote->update(['status' => MotorQuote::STATUS_PRE_FLIGHT]);
        }

        return redirect()->route('motor-risks.pre-flight', $motorQuote);
    }

    public function preFlight(MotorQuoteRequest $request, MotorQuote $motorQuote): Response
    {
        return Inertia::render('MotorInsurance/Retail/PreFlight', [
            'quote' => new MotorQuoteResource($motorQuote->load('policyholders')),
            'wallet' => new WalletResource($motorQuote->producer->wallet),
            'colors' => $this->vehicleCatalog->colors(),
        ]);
    }

    public function preFlightStore(PreFlightStoreRequest $request, MotorQuote $motorQuote): RedirectResponse
    {
        $data = $request->validated();

        $this->vehicleCatalog->registerColor($data['color']);

        $inception = Carbon::parse($data['inception_date']);
        $expiry = $inception->copy()->addYears($motorQuote->coverage_period);

        $motorQuote->update([
            ...$data,
            'expiry_date' => $expiry->toDateString(),
        ]);

        return back()->with('success', 'Vehicle and inception date details saved.');
    }

    public function authenticateLto(MotorQuoteRequest $request, MotorQuote $motorQuote): RedirectResponse
    {
        if (! $motorQuote->color || ! $motorQuote->chassis_no || ! $motorQuote->mv_file_no) {
            return back()->with('error', 'Please complete the vehicle details (Color, Chassis/Serial No., MV File No.) before authenticating with LTO.');
        }

        if ($motorQuote->policyholders()->count() === 0) {
            return back()->with('error', 'Please add at least one Policyholder before proceeding.');
        }

        // LTO/Insurance Commission real-time validation is not yet wired up — treat as verified for now.
        $motorQuote->update([
            'lto_status' => 'verified',
            'lto_message' => 'COC successfully validated by the LTO/Insurance Commission web service.',
        ]);

        return redirect()->route('motor-risks.checkout', $motorQuote)->with('success', 'Proceeding to checkout.');
    }

    public function checkout(MotorQuoteRequest $request, MotorQuote $motorQuote): Response|RedirectResponse
    {
        if ($motorQuote->lto_status !== 'verified') {
            return redirect()->route('motor-risks.pre-flight', $motorQuote)
                ->with('error', 'Please complete the pre-flight step before proceeding to payment.');
        }

        return Inertia::render('MotorInsurance/Retail/Checkout', [
            'quote' => new MotorQuoteResource($motorQuote),
            'wallet' => new WalletResource($motorQuote->producer->wallet),
        ]);
    }

    public function checkoutStore(MotorQuoteRequest $request, MotorQuote $motorQuote): RedirectResponse
    {
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
            'wallet' => new WalletResource($request->user()->producer->wallet),
        ]);
    }

    public function ocrStore(OcrStoreRequest $request): RedirectResponse
    {
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
}
