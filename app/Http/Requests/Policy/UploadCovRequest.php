<?php

namespace App\Http\Requests\Policy;

class UploadCovRequest extends PolicyRequest
{
    public function rules(): array
    {
        return [
            'cov_document' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }
}
