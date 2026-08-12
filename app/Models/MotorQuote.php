<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MotorQuote extends Model
{
    public const STATUS_QUOTE = 'quote';
    public const STATUS_PRE_FLIGHT = 'pre_flight';
    public const STATUS_POLICY = 'policy';
    public const STATUS_REJECTED = 'rejected';

    protected $appends = ['vehicle_title'];

    protected $fillable = [
        'producer_id',
        'quote_ref',
        'status',
        'lto_registration_type',
        'vehicle_class',
        'coverage_period',
        'surplus_vehicle',
        'year_model',
        'brand',
        'model',
        'variant',
        'plate_no',
        'net_premium',
        'doc_stamps_tax',
        'vat',
        'local_govt_tax',
        'lto_dbp_dci_fee',
        'coc_verification_fee',
        'other_charges',
        'total_premium',
        'issuance_price',
        'chassis_no',
        'motor_no',
        'mv_file_no',
        'color',
        'other_info',
        'inception_date',
        'expiry_date',
        'lto_status',
        'lto_message',
        'authentication_no',
    ];

    protected function casts(): array
    {
        return [
            'surplus_vehicle' => 'boolean',
            'net_premium' => 'decimal:2',
            'doc_stamps_tax' => 'decimal:2',
            'vat' => 'decimal:2',
            'local_govt_tax' => 'decimal:2',
            'lto_dbp_dci_fee' => 'decimal:2',
            'coc_verification_fee' => 'decimal:2',
            'other_charges' => 'decimal:2',
            'total_premium' => 'decimal:2',
            'issuance_price' => 'decimal:2',
            'inception_date' => 'date',
            'expiry_date' => 'date',
        ];
    }

    public function producer(): BelongsTo
    {
        return $this->belongsTo(Producer::class);
    }

    public function policyholders(): HasMany
    {
        return $this->hasMany(Policyholder::class);
    }

    public function policy(): HasOne
    {
        return $this->hasOne(Policy::class);
    }

    public function getVehicleTitleAttribute(): string
    {
        return trim("{$this->year_model} {$this->brand} {$this->model} {$this->variant}");
    }
}
