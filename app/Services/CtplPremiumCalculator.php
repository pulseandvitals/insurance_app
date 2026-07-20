<?php

namespace App\Services;

class CtplPremiumCalculator
{
    /**
     * Base annual net premium per vehicle class, in PHP.
     */
    private const BASE_RATES = [
        'Private' => 560.00,
        'Motorcycles' => 325.00,
        'Commercial-Trucks' => 730.00,
        'LTO Tricycle' => 260.00,
        'LTO Taxi' => 600.00,
        'LTO Public Utility Jeepney' => 450.00,
        'LTO Public Utility Bus' => 950.00,
    ];

    public function calculate(string $vehicleClass, int $coveragePeriod): array
    {
        $baseRate = self::BASE_RATES[$vehicleClass] ?? 500.00;

        // 3-year term carries a small discount versus paying for 3 separate years.
        $multiplier = $coveragePeriod === 3 ? 2.85 : 1;
        $netPremium = round($baseRate * $multiplier, 2);

        $docStampsTax = round(10.00 * $coveragePeriod, 2);
        $vat = 0.00; // CTPL premiums are VAT-exempt.
        $localGovtTax = round($netPremium * 0.0075, 2);
        $ltoDbpDciFee = round(25.00 * $coveragePeriod, 2);
        $cocVerificationFee = round(40.00 * $coveragePeriod, 2);
        $otherCharges = 15.00;

        $totalPremium = round(
            $netPremium + $docStampsTax + $vat + $localGovtTax + $ltoDbpDciFee + $cocVerificationFee + $otherCharges,
            2,
        );

        return [
            'net_premium' => $netPremium,
            'doc_stamps_tax' => $docStampsTax,
            'vat' => $vat,
            'local_govt_tax' => $localGovtTax,
            'lto_dbp_dci_fee' => $ltoDbpDciFee,
            'coc_verification_fee' => $cocVerificationFee,
            'other_charges' => $otherCharges,
            'total_premium' => $totalPremium,
        ];
    }
}
