<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalPricing extends Model
{
    protected $table = 'global_pricing';

    protected $fillable = [
        'motorcycle_base_rate',
        'pc_suv_base_rate',
        'cv_truck_base_rate',
        'motorcycle_remittance_rate',
        'pc_suv_remittance_rate',
        'cv_truck_remittance_rate',
        'doc_stamp_tax_percent',
        'vat_percent',
        'local_govt_tax_percent',
        'lto_dbp_dci_fee',
    ];

    protected function casts(): array
    {
        return [
            'motorcycle_base_rate' => 'decimal:2',
            'pc_suv_base_rate' => 'decimal:2',
            'cv_truck_base_rate' => 'decimal:2',
            'motorcycle_remittance_rate' => 'decimal:2',
            'pc_suv_remittance_rate' => 'decimal:2',
            'cv_truck_remittance_rate' => 'decimal:2',
            'doc_stamp_tax_percent' => 'decimal:2',
            'vat_percent' => 'decimal:2',
            'local_govt_tax_percent' => 'decimal:2',
            'lto_dbp_dci_fee' => 'decimal:2',
        ];
    }

    /**
     * The single global pricing row, lazily created with the documented
     * defaults on first access so no seeder is required.
     */
    public static function current(): self
    {
        return self::query()->firstOrCreate([], [
            'motorcycle_base_rate' => 360.00,
            'pc_suv_base_rate' => 660.00,
            'cv_truck_base_rate' => 1300.00,
            'motorcycle_remittance_rate' => 145.00,
            'pc_suv_remittance_rate' => 245.00,
            'cv_truck_remittance_rate' => 425.00,
            'doc_stamp_tax_percent' => 12.50,
            'vat_percent' => 12.00,
            'local_govt_tax_percent' => 0.50,
            'lto_dbp_dci_fee' => 60.00,
        ]);
    }
}
