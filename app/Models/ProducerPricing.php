<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProducerPricing extends Model
{
    protected $table = 'producer_pricing';

    protected $fillable = [
        'producer_id',
        'others_fee',
        'coc_verification_fee',
        'motorcycle_price',
        'pc_suv_price',
        'cv_truck_price',
    ];

    protected function casts(): array
    {
        return [
            'others_fee' => 'decimal:2',
            'coc_verification_fee' => 'decimal:2',
            'motorcycle_price' => 'decimal:2',
            'pc_suv_price' => 'decimal:2',
            'cv_truck_price' => 'decimal:2',
        ];
    }

    public function producer(): BelongsTo
    {
        return $this->belongsTo(Producer::class);
    }
}
