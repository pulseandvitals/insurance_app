<?php

namespace App\Http\Requests\Wallet;

class WalletFilterRequest extends WalletRequest
{
    public function rules(): array
    {
        return [
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ];
    }
}
