<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isSuperAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'branch_id' => ['required', 'exists:branches,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:20'],
            'address' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'others_fee' => ['required', 'numeric', 'min:0'],
            'coc_verification_fee' => ['required', 'numeric', 'min:0'],
            'motorcycle_price' => ['required', 'numeric', 'min:0'],
            'pc_suv_price' => ['required', 'numeric', 'min:0'],
            'cv_truck_price' => ['required', 'numeric', 'min:0'],
        ];
    }
}
