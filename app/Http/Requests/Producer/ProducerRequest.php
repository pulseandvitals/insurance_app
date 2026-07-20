<?php

namespace App\Http\Requests\Producer;

use App\Models\Producer;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Base request for any action scoped to a {producer} route-model binding.
 * Ensures the authenticated user can only ever act on their own producer record.
 */
class ProducerRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Producer $producer */
        $producer = $this->route('producer');

        return $producer && $this->user()->producer?->id === $producer->id;
    }

    public function rules(): array
    {
        return [];
    }
}
