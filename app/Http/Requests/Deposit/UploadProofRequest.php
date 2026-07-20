<?php

namespace App\Http\Requests\Deposit;

class UploadProofRequest extends DepositOwnershipRequest
{
    public function rules(): array
    {
        return [
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }
}
