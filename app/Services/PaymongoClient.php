<?php

namespace App\Services;

use App\Models\Deposit;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class PaymongoClient
{
    private const BASE_URL = 'https://api.paymongo.com/v1';

    private const PAYMENT_METHOD_TYPES = ['qrph', 'gcash', 'paymaya'];

    /**
     * Create a hosted Checkout Session for a wallet deposit, restricted to
     * QRPH, GCash, and Maya. Returns the decoded `data` object, which
     * includes `id` and `attributes.checkout_url` to redirect the payer to.
     */
    public function createCheckoutSession(Deposit $deposit): array
    {
        $response = $this->client()->post('/checkout_sessions', [
            'data' => [
                'attributes' => [
                    'send_email_receipt' => false,
                    'show_description' => true,
                    'show_line_items' => true,
                    'description' => "Wallet deposit {$deposit->ref_no}",
                    'line_items' => [[
                        'currency' => 'PHP',
                        'amount' => (int) round($deposit->amount * 100),
                        'name' => 'Producer Wallet Deposit',
                        'description' => $deposit->ref_no,
                        'quantity' => 1,
                    ]],
                    'payment_method_types' => self::PAYMENT_METHOD_TYPES,
                    'reference_number' => $deposit->ref_no,
                    'success_url' => route('topups.callback', $deposit),
                    'cancel_url' => route('topups.index'),
                ],
            ],
        ])->throw();

        return $response->json('data');
    }

    /**
     * Retrieve the current state of a Checkout Session — used to
     * authoritatively verify payment on the success_url callback rather
     * than trusting the redirect itself.
     */
    public function retrieveCheckoutSession(string $checkoutSessionId): array
    {
        return $this->client()
            ->get("/checkout_sessions/{$checkoutSessionId}")
            ->throw()
            ->json('data');
    }

    private function client(): PendingRequest
    {
        return Http::baseUrl(self::BASE_URL)
            ->withBasicAuth(config('services.paymongo.secret_key'), '')
            ->asJson()
            ->acceptJson();
    }
}
