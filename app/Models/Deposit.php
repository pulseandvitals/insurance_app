<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deposit extends Model
{
    public const STATUS_INITIATED_ONLINE = 'INITIATED ONLINE PAYMENT';
    public const STATUS_PAID_ONLINE_CONFIRMED = 'PAID ONLINE CONFIRMED';
    public const STATUS_AWAITING_PROOF = 'AWAITING PROOF OF PAYMENT';
    public const STATUS_BANK_PAYMENT_CONFIRMED = 'BANK PAYMENT CONFIRMED';

    protected $fillable = [
        'producer_id',
        'ref_no',
        'amount',
        'status',
        'deposit_type',
        'approved_by',
        'approved_date',
        'proof_path',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'approved_date' => 'datetime',
        ];
    }

    public function producer(): BelongsTo
    {
        return $this->belongsTo(Producer::class);
    }

    public function isConfirmed(): bool
    {
        return in_array($this->status, [self::STATUS_PAID_ONLINE_CONFIRMED, self::STATUS_BANK_PAYMENT_CONFIRMED]);
    }

    public function needsPayment(): bool
    {
        return $this->status === self::STATUS_INITIATED_ONLINE;
    }

    public function needsUpload(): bool
    {
        return $this->status === self::STATUS_AWAITING_PROOF;
    }
}
