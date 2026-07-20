<?php

namespace App\Http\Requests\MotorQuote;

use Illuminate\Foundation\Http\FormRequest;

class QuotationsIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->producer !== null;
    }

    public function rules(): array
    {
        return [
            'transaction_type' => ['nullable', 'string'],
            'plate_no' => ['nullable', 'string', 'max:20'],
            'quote_ref' => ['nullable', 'string', 'max:50'],
        ];
    }
}
