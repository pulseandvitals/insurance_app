<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Policyholder extends Model
{
    protected $fillable = [
        'motor_quote_id',
        'type',
        'name',
        'address',
        'use_as_address',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'use_as_address' => 'boolean',
            'payload' => 'array',
        ];
    }

    public function motorQuote(): BelongsTo
    {
        return $this->belongsTo(MotorQuote::class);
    }
}
