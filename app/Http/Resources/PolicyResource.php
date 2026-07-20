<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Policy */
class PolicyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'online_policy_no' => $this->online_policy_no,
            'genweb_code' => $this->genweb_code,
            'coc_no' => $this->coc_no,
            'authentication_no' => $this->authentication_no,
            'has_cov' => $this->has_cov,
            'is_direct' => $this->is_direct,
            'issued_at' => $this->issued_at,
            'contract_from' => $this->contract_from,
            'contract_to' => $this->contract_to,

            'motor_quote' => new MotorQuoteResource($this->whenLoaded('motorQuote')),
            'producer' => new ProducerResource($this->whenLoaded('producer')),
        ];
    }
}
