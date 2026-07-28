<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\MotorQuote */
class MotorQuoteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quote_ref' => $this->quote_ref,
            'status' => $this->status,

            'lto_registration_type' => $this->lto_registration_type,
            'vehicle_class' => $this->vehicle_class,
            'coverage_period' => $this->coverage_period,
            'surplus_vehicle' => $this->surplus_vehicle,
            'year_model' => $this->year_model,
            'brand' => $this->brand,
            'model' => $this->model,
            'variant' => $this->variant,
            'plate_no' => $this->plate_no,
            'vehicle_title' => $this->vehicle_title,

            'net_premium' => $this->net_premium,
            'doc_stamps_tax' => $this->doc_stamps_tax,
            'vat' => $this->vat,
            'local_govt_tax' => $this->local_govt_tax,
            'lto_dbp_dci_fee' => $this->lto_dbp_dci_fee,
            'coc_verification_fee' => $this->coc_verification_fee,
            'other_charges' => $this->other_charges,
            'total_premium' => $this->total_premium,
            'issuance_price' => $this->issuance_price,

            'chassis_no' => $this->chassis_no,
            'motor_no' => $this->motor_no,
            'mv_file_no' => $this->mv_file_no,
            'color' => $this->color,
            'other_info' => $this->other_info,
            'inception_date' => $this->inception_date,
            'expiry_date' => $this->expiry_date,

            'lto_status' => $this->lto_status,
            'lto_message' => $this->lto_message,

            'created_at' => $this->created_at,

            'policyholders' => PolicyholderResource::collection($this->whenLoaded('policyholders')),
            'policy' => $this->whenLoaded('policy', fn () => $this->policy ? ['id' => $this->policy->id] : null),
        ];
    }
}
