<?php

namespace App\Http\Requests\MotorQuote;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class StoreMotorQuoteRequest extends FormRequest
{
    /** Vehicle class + coverage-period combinations with no prepaid setup on file (demo constraint). */
    private const NO_PREPAID_SETUP = [
        ['LTO Public Utility Bus', 3],
    ];

    public function authorize(): bool
    {
        return $this->user()->producer !== null;
    }

    public function rules(): array
    {
        return [
            'lto_registration_type' => ['required', 'in:New,Renewal'],
            'vehicle_class' => ['required', 'in:Private,Motorcycles,Commercial-Trucks,LTO Tricycle,LTO Taxi,LTO Public Utility Jeepney,LTO Public Utility Bus'],
            'coverage_period' => ['required', 'in:1,3'],
            'surplus_vehicle' => ['required', 'boolean'],
            'year_model' => ['required', 'integer', 'min:1965', 'max:2027'],
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'variant' => ['required', 'string', 'max:100'],
            'plate_no' => ['required', 'string', 'max:20'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $data = $validator->getData();

            if (($data['lto_registration_type'] ?? null) === 'New'
                && empty($data['surplus_vehicle'])
                && (int) ($data['coverage_period'] ?? 0) === 1
            ) {
                $validator->errors()->add(
                    'coverage_period',
                    "A 1-year CTPL term for 'New' registration type is only allowed for Surplus vehicles. Please select the 3-Year coverage period, or mark this as a Surplus Vehicle.",
                );
            }

            $yearModel = (int) ($data['year_model'] ?? 0);
            $currentYear = (int) now()->format('Y');

            if ($yearModel >= $currentYear && ($data['lto_registration_type'] ?? null) !== 'New') {
                $validator->errors()->add(
                    'lto_registration_type',
                    "Vehicles with year model {$yearModel} or later must use Registration Type: New.",
                );
            }

            foreach (self::NO_PREPAID_SETUP as [$class, $term]) {
                if (($data['vehicle_class'] ?? null) === $class && (int) ($data['coverage_period'] ?? 0) === $term) {
                    $validator->errors()->add(
                        'vehicle_class',
                        "No Prepaid setup for this producer for subline: {$class}, term: {$term} year/s.",
                    );
                }
            }
        });
    }
}
