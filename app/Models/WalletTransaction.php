<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    public const TYPE_PAYMENT_CTPL = 'PAYMENT CTPL TRANSACTION';
    public const TYPE_DEPOSIT = 'DEPOSIT';

    protected $fillable = [
        'wallet_id',
        'from_handle',
        'to_handle',
        'transaction_type',
        'reference_label',
        'ref_no',
        'debit',
        'credit',
    ];

    protected function casts(): array
    {
        return [
            'debit' => 'decimal:2',
            'credit' => 'decimal:2',
        ];
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
