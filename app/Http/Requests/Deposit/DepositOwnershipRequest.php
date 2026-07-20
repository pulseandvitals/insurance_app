<?php

namespace App\Http\Requests\Deposit;

use App\Models\Deposit;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Base request for any action scoped to a {deposit} route-model binding.
 */
class DepositOwnershipRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var Deposit $deposit */
        $deposit = $this->route('deposit');

        return $deposit && $this->user()->producer?->id === $deposit->producer_id;
    }

    public function rules(): array
    {
        return [];
    }
}
