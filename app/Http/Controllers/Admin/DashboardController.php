<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\GlobalPricing;
use App\Models\MotorQuote;
use App\Models\User;
use App\Services\CtplPremiumCalculator;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'producers_count' => User::whereHas('roles', fn ($q) => $q->where('name', User::ROLE_PRODUCER))->count(),
                'branches_count' => Branch::count(),
            ],
            'issuance' => $this->issuanceSummary(),
        ]);
    }

    /**
     * Totals every completed (paid) issuance across all producers, bucketed
     * into the 3 pricing categories. "Income" is what producers' wallets
     * were actually charged (motor_quotes.issuance_price); "remittance" is
     * what head office is owed at the currently configured rate; "profit"
     * is the difference the platform keeps.
     */
    private function issuanceSummary(): array
    {
        $pricing = GlobalPricing::current();

        $labels = [
            'motorcycle' => 'Motorcycle',
            'pc_suv' => 'PC / SUV',
            'cv_truck' => 'Truck / Commercial',
        ];

        $remittanceRates = [
            'motorcycle' => (float) $pricing->motorcycle_remittance_rate,
            'pc_suv' => (float) $pricing->pc_suv_remittance_rate,
            'cv_truck' => (float) $pricing->cv_truck_remittance_rate,
        ];

        $categories = [];
        foreach ($labels as $key => $label) {
            $categories[$key] = ['label' => $label, 'policies' => 0, 'income' => 0.0, 'remittance' => 0.0, 'profit' => 0.0];
        }

        $quotes = MotorQuote::query()
            ->where('status', MotorQuote::STATUS_POLICY)
            ->get(['vehicle_class', 'issuance_price', 'coverage_period']);

        foreach ($quotes as $quote) {
            $key = CtplPremiumCalculator::CATEGORY_MAP[$quote->vehicle_class] ?? 'pc_suv';
            $income = (float) $quote->issuance_price;
            $remittance = $remittanceRates[$key] * $quote->coverage_period;

            $categories[$key]['policies']++;
            $categories[$key]['income'] += $income;
            $categories[$key]['remittance'] += $remittance;
            $categories[$key]['profit'] += $income - $remittance;
        }

        $totals = ['policies' => 0, 'income' => 0.0, 'remittance' => 0.0, 'profit' => 0.0];

        foreach ($categories as $key => $row) {
            $categories[$key]['income'] = round($row['income'], 2);
            $categories[$key]['remittance'] = round($row['remittance'], 2);
            $categories[$key]['profit'] = round($row['profit'], 2);

            $totals['policies'] += $row['policies'];
            $totals['income'] += $row['income'];
            $totals['remittance'] += $row['remittance'];
            $totals['profit'] += $row['profit'];
        }

        $totals['income'] = round($totals['income'], 2);
        $totals['remittance'] = round($totals['remittance'], 2);
        $totals['profit'] = round($totals['profit'], 2);

        return ['categories' => $categories, 'totals' => $totals];
    }
}
