<?php

namespace App\Http\Requests\Wallet;

class DepositRequest extends WalletRequest
{
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:100'],
            'payment_method' => ['required', 'in:Online Payment,Pay Through Bills Payment'],
        ];
    }
}
