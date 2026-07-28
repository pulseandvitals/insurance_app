<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GlobalPricingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isSuperAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'motorcycle_base_rate' => ['required', 'numeric', 'min:0'],
            'pc_suv_base_rate' => ['required', 'numeric', 'min:0'],
            'cv_truck_base_rate' => ['required', 'numeric', 'min:0'],
            'doc_stamp_tax_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'vat_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'local_govt_tax_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'lto_dbp_dci_fee' => ['required', 'numeric', 'min:0'],
        ];
    }
}
