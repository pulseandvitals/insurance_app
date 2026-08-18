<?php

namespace App\Http\Requests\MotorQuote;

class CheckoutStoreRequest extends MotorQuoteRequest
{
    public function rules(): array
    {
        return [
            'terms_accepted' => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'terms_accepted' => 'You must agree to the Terms and Conditions before paying and issuing the policy.',
        ];
    }
}
