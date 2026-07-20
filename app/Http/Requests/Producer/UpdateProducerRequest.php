<?php

namespace App\Http\Requests\Producer;

class UpdateProducerRequest extends ProducerRequest
{
    public function rules(): array
    {
        return [
            'password' => ['nullable', 'confirmed', 'min:8'],
            'consent' => ['accepted'],
        ];
    }
}
