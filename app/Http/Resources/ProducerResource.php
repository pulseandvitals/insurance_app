<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Producer */
class ProducerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'suffix' => $this->suffix,
            'full_name' => $this->full_name,
            'status' => $this->status,
            'is_oa' => $this->is_oa,
            'tier' => $this->tier,
            'address' => $this->address,
            'email' => $this->email,
            'phone' => $this->phone,
            'branch' => $this->branch ? [
                'id' => $this->branch->id,
                'code' => $this->branch->code,
                'name' => $this->branch->name,
            ] : null,
            'wallet_handle' => $this->wallet_handle,
            'created_at' => $this->created_at,
        ];
    }
}
