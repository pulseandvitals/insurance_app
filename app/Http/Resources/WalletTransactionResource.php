<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\WalletTransaction */
class WalletTransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'from_handle' => $this->from_handle,
            'to_handle' => $this->to_handle,
            'transaction_type' => $this->transaction_type,
            'reference_label' => $this->reference_label,
            'ref_no' => $this->ref_no,
            'debit' => $this->debit,
            'credit' => $this->credit,
            'created_at' => $this->created_at,
        ];
    }
}
