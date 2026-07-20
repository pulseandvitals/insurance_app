<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $fillable = [
        'producer_id',
        'balance',
        'initial_deposit',
        'accumulated_deposit',
        'total_net_remittance',
        'total_received',
    ];

    protected function casts(): array
    {
        return [
            'balance' => 'decimal:2',
            'initial_deposit' => 'decimal:2',
            'accumulated_deposit' => 'decimal:2',
            'total_net_remittance' => 'decimal:2',
            'total_received' => 'decimal:2',
        ];
    }

    public function producer(): BelongsTo
    {
        return $this->belongsTo(Producer::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }
}
