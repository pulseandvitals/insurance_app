<?php

namespace App\Http\Requests\Policy;

use Illuminate\Foundation\Http\FormRequest;

class RenewalSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->producer !== null;
    }

    public function rules(): array
    {
        return [
            'online_policy_no' => ['nullable', 'required_without_all:chassis_no,motor_no', 'string'],
            'chassis_no' => ['nullable', 'required_without_all:online_policy_no,motor_no', 'string'],
            'motor_no' => ['nullable', 'required_without_all:online_policy_no,chassis_no', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'online_policy_no.required_without_all' => 'Please enter at least one of the fields to search.',
            'chassis_no.required_without_all' => 'Please enter at least one of the fields to search.',
            'motor_no.required_without_all' => 'Please enter at least one of the fields to search.',
        ];
    }
}
