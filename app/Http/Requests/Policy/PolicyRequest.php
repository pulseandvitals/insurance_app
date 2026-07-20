<?php

namespace App\Http\Requests\Policy;

use App\Models\Policy;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Base request for any action scoped to a {policy} route-model binding
 * (contract detail, print views, PDF download).
 */
class PolicyRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Policy $policy */
        $policy = $this->route('policy');

        return $policy && $this->user()->producer?->id === $policy->producer_id;
    }

    public function rules(): array
    {
        return [];
    }
}
