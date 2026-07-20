<?php

namespace App\Http\Requests\Wallet;

class DepositRequest extends WalletRequest
{
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:100'],
            'payment_method' => ['required', 'in:Pay with Credit/Debit Card,GCash,GrabPay,Pay Through Bills Payment'],
        ];
    }
}
