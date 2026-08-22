<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Policy extends Model
{
    protected $fillable = [
        'motor_quote_id',
        'producer_id',
        'online_policy_no',
        'genweb_code',
        'coc_no',
        'authentication_no',
        'has_cov',
        'cov_document_path',
        'is_direct',
        'issued_at',
        'contract_from',
        'contract_to',
    ];

    protected function casts(): array
    {
        return [
            'has_cov' => 'boolean',
            'is_direct' => 'boolean',
            'issued_at' => 'datetime',
            'contract_from' => 'date',
            'contract_to' => 'date',
        ];
    }

    public function motorQuote(): BelongsTo
    {
        return $this->belongsTo(MotorQuote::class);
    }

    public function producer(): BelongsTo
    {
        return $this->belongsTo(Producer::class);
    }
}
