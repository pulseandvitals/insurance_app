<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Wallet */
class WalletResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'balance' => $this->balance,
            'initial_deposit' => $this->initial_deposit,
            'accumulated_deposit' => $this->accumulated_deposit,
            'total_net_remittance' => $this->total_net_remittance,
            'total_received' => $this->total_received,
        ];
    }
}
