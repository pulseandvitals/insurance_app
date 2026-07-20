<?php

namespace App\Http\Requests\Policy;

use Illuminate\Foundation\Http\FormRequest;

class PoliciesIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->producer !== null;
    }

    public function rules(): array
    {
        return [
            'assured_name' => ['nullable', 'string', 'max:150'],
            'online_policy_no' => ['nullable', 'string', 'max:50'],
            'chassis_no' => ['nullable', 'string', 'max:30'],
            'motor_no' => ['nullable', 'string', 'max:30'],
            'cov_status' => ['nullable', 'in:All,No COV,With COV'],
            'direct_status' => ['nullable', 'in:All,Yes,No'],
        ];
    }
}
