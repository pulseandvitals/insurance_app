<?php

namespace App\Http\Requests\Producer;

class DashboardFilterRequest extends ProducerRequest
{
    public function rules(): array
    {
        return [
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
        ];
    }
}
