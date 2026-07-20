<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Deposit */
class DepositResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ref_no' => $this->ref_no,
            'amount' => $this->amount,
            'status' => $this->status,
            'deposit_type' => $this->deposit_type,
            'approved_by' => $this->approved_by,
            'approved_date' => $this->approved_date,
            'needs_payment' => $this->needsPayment(),
            'needs_upload' => $this->needsUpload(),
            'created_at' => $this->created_at,
        ];
    }
}
