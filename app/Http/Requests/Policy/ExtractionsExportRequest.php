<?php

namespace App\Http\Requests\Policy;

use Illuminate\Foundation\Http\FormRequest;

class ExtractionsExportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->producer !== null;
    }

    public function rules(): array
    {
        return [
            'report_type' => ['nullable', 'in:Detailed Report'],
            'paid_unpaid' => ['nullable', 'in:All,PAID,UNPAID'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ];
    }
}
