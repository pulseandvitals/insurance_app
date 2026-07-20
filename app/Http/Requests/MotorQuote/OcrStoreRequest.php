<?php

namespace App\Http\Requests\MotorQuote;

use Illuminate\Foundation\Http\FormRequest;

class OcrStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->producer !== null;
    }

    public function rules(): array
    {
        return [
            'document_type' => ['required', 'in:Certificate of Registration (CR)'],
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:8192'],
            'consent' => ['accepted'],
        ];
    }
}
