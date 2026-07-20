<?php

namespace App\Http\Requests\Policy;

use Illuminate\Foundation\Http\FormRequest;

class BatchCocExportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->producer !== null;
    }

    public function rules(): array
    {
        return [
            'date_from' => ['required', 'date'],
            'date_to' => ['required', 'date', 'after_or_equal:date_from'],
        ];
    }
}
