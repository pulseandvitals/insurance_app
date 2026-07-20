<?php

namespace App\Http\Requests\Wallet;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Base request for actions against the authenticated user's own e-wallet.
 * There is no route-model binding here — the wallet is always resolved
 * from the authenticated producer, so authorization just confirms one exists.
 */
class WalletRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->producer !== null;
    }

    public function rules(): array
    {
        return [];
    }
}
