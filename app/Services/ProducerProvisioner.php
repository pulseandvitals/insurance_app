<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Deposit;
use App\Models\MotorQuote;
use App\Models\Policy;
use App\Models\Policyholder;
use App\Models\Producer;
use App\Models\ProducerPricing;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Str;

class ProducerProvisioner
{
    public function __construct(private readonly CtplPremiumCalculator $calculator) {}

    public function provision(User $user, ?Branch $branch = null): Producer
    {
        if ($user->producer) {
            return $user->producer;
        }

        $branch ??= Branch::query()->orderBy('id')->firstOrFail();

        [$firstName, $lastName] = $this->splitName($user->name);

        $producer = Producer::create([
            'user_id' => $user->id,
            'code' => 'SICI-'.str_pad((string) $user->id, 6, '0', STR_PAD_LEFT),
            'first_name' => $firstName,
            'middle_name' => null,
            'last_name' => $lastName,
            'suffix' => null,
            'status' => 'Active',
            'is_oa' => true,
            'tier' => 'Regular',
            'address' => 'Unit 12B, Fortune Bldg., Ayala Ave., Makati City, Metro Manila',
            'email' => $user->email,
            'phone' => '0917'.random_int(1000000, 9999999),
            'branch_id' => $branch->id,
        ]);

        $wallet = Wallet::create([
            'producer_id' => $producer->id,
            'balance' => 0,
            'initial_deposit' => 0,
            'accumulated_deposit' => 0,
            'total_net_remittance' => 0,
            'total_received' => 0,
        ]);

        ProducerPricing::create(['producer_id' => $producer->id]);

        $this->seedDeposits($producer, $wallet);
        $this->seedMotorHistory($producer, $wallet);

        // The initial `if ($user->producer)` check above cached a null result on
        // this relation; refresh it so callers in the same request see the row
        // we just created instead of the stale cached null.
        $user->setRelation('producer', $producer);

        return $producer;
    }

    private function splitName(string $name): array
    {
        $parts = preg_split('/\s+/', trim($name), 2);

        return [$parts[0] ?? 'Juan', $parts[1] ?? 'Producer'];
    }

    private function seedDeposits(Producer $producer, Wallet $wallet): void
    {
        $confirmed = Deposit::create([
            'producer_id' => $producer->id,
            'ref_no' => 'DEP-'.strtoupper(Str::random(8)),
            'amount' => 25000.00,
            'status' => Deposit::STATUS_PAID_ONLINE_CONFIRMED,
            'deposit_type' => 'GCash',
            'approved_by' => 'System Auto-Approval',
            'approved_date' => now()->subDays(21),
            'created_at' => now()->subDays(21),
            'updated_at' => now()->subDays(21),
        ]);

        $wallet->increment('balance', $confirmed->amount);
        $wallet->increment('initial_deposit', $confirmed->amount);
        $wallet->increment('accumulated_deposit', $confirmed->amount);
        $wallet->increment('total_received', $confirmed->amount);

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'from_handle' => 'SICI Treasury',
            'to_handle' => $producer->wallet_handle,
            'transaction_type' => WalletTransaction::TYPE_DEPOSIT,
            'reference_label' => 'Deposit UUID: '.$confirmed->ref_no,
            'ref_no' => $confirmed->ref_no,
            'debit' => 0,
            'credit' => $confirmed->amount,
            'created_at' => now()->subDays(21),
            'updated_at' => now()->subDays(21),
        ]);

        Deposit::create([
            'producer_id' => $producer->id,
            'ref_no' => 'DEP-'.strtoupper(Str::random(8)),
            'amount' => 5000.00,
            'status' => Deposit::STATUS_INITIATED_ONLINE,
            'deposit_type' => 'GrabPay',
            'created_at' => now()->subHours(6),
            'updated_at' => now()->subHours(6),
        ]);

        Deposit::create([
            'producer_id' => $producer->id,
            'ref_no' => 'DEP-'.strtoupper(Str::random(8)),
            'amount' => 10000.00,
            'status' => Deposit::STATUS_AWAITING_PROOF,
            'deposit_type' => 'Payment via Bills Payment',
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);
    }

    private function seedMotorHistory(Producer $producer, Wallet $wallet): void
    {
        $samples = [
            ['Private', 2022, 'Toyota', 'Vios', '1.3 XE CVT', 'NBA1234', MotorQuote::STATUS_POLICY],
            ['Motorcycles', 2023, 'Honda', 'Click 125i', 'Standard', '9821NC', MotorQuote::STATUS_POLICY],
            ['Private', 2024, 'Mitsubishi', 'Mirage G4', 'GLX MT', 'NEW', MotorQuote::STATUS_QUOTE],
        ];

        foreach ($samples as [$vehicleClass, $year, $brand, $model, $variant, $plate, $status]) {
            $premium = $this->calculator->calculate($vehicleClass, 1, $producer);
            $createdAt = now()->subDays(random_int(1, 18));

            $quote = MotorQuote::create([
                'producer_id' => $producer->id,
                'quote_ref' => 'QR-'.strtoupper(Str::random(10)),
                'status' => $status,
                'lto_registration_type' => $plate === 'NEW' ? 'New' : 'Renewal',
                'vehicle_class' => $vehicleClass,
                'coverage_period' => 1,
                'surplus_vehicle' => false,
                'year_model' => $year,
                'brand' => $brand,
                'model' => $model,
                'variant' => $variant,
                'plate_no' => $plate,
                ...$premium,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            if ($status !== MotorQuote::STATUS_POLICY) {
                continue;
            }

            $quote->update([
                'chassis_no' => strtoupper(Str::random(15)),
                'motor_no' => strtoupper(Str::random(12)),
                'mv_file_no' => strtoupper(Str::random(15)),
                'color' => 'White',
                'inception_date' => $createdAt->toDateString(),
                'expiry_date' => $createdAt->copy()->addYear()->toDateString(),
                'lto_status' => 'verified',
                'lto_message' => 'COC successfully validated by LTO/Insurance Commission web service.',
            ]);

            Policyholder::create([
                'motor_quote_id' => $quote->id,
                'type' => 'person',
                'name' => $producer->full_name,
                'address' => $producer->address,
                'use_as_address' => true,
                'payload' => ['email' => $producer->email, 'contact_number' => $producer->phone],
            ]);

            $policy = Policy::create([
                'motor_quote_id' => $quote->id,
                'producer_id' => $producer->id,
                'online_policy_no' => 'OP-'.now()->format('Y').'-'.str_pad((string) $quote->id, 6, '0', STR_PAD_LEFT),
                'genweb_code' => 'GW'.strtoupper(Str::random(8)),
                'coc_no' => 'COC-'.strtoupper(Str::random(10)),
                'authentication_no' => 'AUTH-'.strtoupper(Str::random(10)),
                'issued_at' => $createdAt,
                'contract_from' => $createdAt->toDateString(),
                'contract_to' => $createdAt->copy()->addYear()->toDateString(),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            $wallet->decrement('balance', $premium['issuance_price']);
            $wallet->increment('total_net_remittance', $premium['issuance_price']);

            WalletTransaction::create([
                'wallet_id' => $wallet->id,
                'from_handle' => $producer->wallet_handle,
                'to_handle' => null,
                'transaction_type' => WalletTransaction::TYPE_PAYMENT_CTPL,
                'reference_label' => 'Policy ID: '.$policy->id,
                'ref_no' => $policy->online_policy_no,
                'debit' => $premium['issuance_price'],
                'credit' => 0,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
    }
}
