<?php

namespace App\Services;

use App\Models\GlobalPricing;
use App\Models\Producer;

class CtplPremiumCalculator
{
    /**
     * Maps the free-form vehicle_class values used on motor quotes to the
     * 3 pricing categories that Global/Producer pricing is configured by.
     * Anything not listed (including unrecognized future values) falls
     * back to 'pc_suv'.
     */
    public const CATEGORY_MAP = [
        'Motorcycles' => 'motorcycle',
        'Private' => 'pc_suv',
        'Commercial-Trucks' => 'cv_truck',
        'LTO Tricycle' => 'cv_truck',
        'LTO Taxi' => 'cv_truck',
        'LTO Public Utility Jeepney' => 'cv_truck',
        'LTO Public Utility Bus' => 'cv_truck',
    ];

    /**
     * The configured base rate is the TOTAL policy price (taxes and fees
     * included), not an amount taxes/fees are added on top of. Net premium
     * is therefore solved backwards from that total so that:
     *
     *   net + (net * taxRate) + fixedFees == totalPremium
     *
     * Any sub-centavo rounding residual from rounding each line item to 2
     * decimals is folded back into net premium so the stored breakdown
     * always sums to exactly the configured total.
     */
    public function calculate(string $vehicleClass, int $coveragePeriod, Producer $producer): array
    {
        $category = self::CATEGORY_MAP[$vehicleClass] ?? 'pc_suv';
        $global = GlobalPricing::current();
        $pricing = $producer->pricing;

        $totalPremium = round($global->{"{$category}_base_rate"} * $coveragePeriod, 2);

        $ltoDbpDciFee = (float) $global->lto_dbp_dci_fee;
        $otherCharges = (float) $pricing->others_fee;
        $cocVerificationFee = (float) $pricing->coc_verification_fee;
        $fixedFees = $ltoDbpDciFee + $otherCharges + $cocVerificationFee;

        $taxRate = ($global->doc_stamp_tax_percent + $global->vat_percent + $global->local_govt_tax_percent) / 100;

        $netPremium = round(($totalPremium - $fixedFees) / (1 + $taxRate), 2);
        $docStampsTax = round($netPremium * $global->doc_stamp_tax_percent / 100, 2);
        $vat = round($netPremium * $global->vat_percent / 100, 2);
        $localGovtTax = round($netPremium * $global->local_govt_tax_percent / 100, 2);

        $roundingResidual = $totalPremium - ($netPremium + $docStampsTax + $vat + $localGovtTax + $fixedFees);
        $netPremium = round($netPremium + $roundingResidual, 2);

        return [
            'net_premium' => $netPremium,
            'doc_stamps_tax' => $docStampsTax,
            'vat' => $vat,
            'local_govt_tax' => $localGovtTax,
            'lto_dbp_dci_fee' => $ltoDbpDciFee,
            'coc_verification_fee' => $cocVerificationFee,
            'other_charges' => $otherCharges,
            'total_premium' => $totalPremium,
            'issuance_price' => round($pricing->{"{$category}_price"} * $coveragePeriod, 2),
        ];
    }
}
