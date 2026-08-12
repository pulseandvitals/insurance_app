<?php

namespace App\Http\Requests\MotorQuote;

class PreFlightStoreRequest extends MotorQuoteRequest
{
    public function rules(): array
    {
        return [
            'authentication_no' => ['required', 'string', 'max:50'],
            'chassis_no' => ['required', 'string', 'max:30'],
            'motor_no' => ['required', 'string', 'max:30'],
            'mv_file_no' => ['required', 'string', 'max:30'],
            'color' => ['required', 'string', 'max:60'],
            'other_info' => ['nullable', 'string', 'max:255'],
            'inception_date' => ['required', 'date'],
        ];
    }
}
