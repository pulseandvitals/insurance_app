<?php

namespace App\Http\Requests\Policyholder;

class StorePolicyholderRequest extends PolicyholderRequest
{
    public function rules(): array
    {
        return match ($this->input('type')) {
            'person' => [
                'type' => ['required', 'in:person'],
                'first_name' => ['required', 'string', 'max:100'],
                'middle_name' => ['nullable', 'string', 'max:100'],
                'last_name' => ['required', 'string', 'max:100'],
                'suffix' => ['nullable', 'string', 'max:10'],
                'birth_day' => ['nullable', 'string', 'max:2'],
                'birth_year' => ['nullable', 'string', 'max:4'],
                'street' => ['required', 'string', 'max:150'],
                'email' => ['required', 'email', 'max:150'],
                'region' => ['required', 'string', 'max:100'],
                'province' => ['required', 'string', 'max:100'],
                'city' => ['required', 'string', 'max:100'],
                'barangay' => ['nullable', 'string', 'max:100'],
                'contact_number' => ['required', 'string', 'max:15'],
                'consent' => ['accepted'],
            ],
            'organization' => [
                'type' => ['required', 'in:organization'],
                'name' => ['required', 'string', 'max:150'],
                'address_line_1' => ['required', 'string', 'max:150'],
                'region' => ['required', 'string', 'max:100'],
                'province' => ['required', 'string', 'max:100'],
                'city' => ['required', 'string', 'max:100'],
                'barangay' => ['required', 'string', 'max:100'],
                'zip_code' => ['nullable', 'string', 'max:10'],
                'email' => ['nullable', 'email', 'max:150'],
                'contact_number' => ['nullable', 'string', 'max:15'],
            ],
            'lender' => [
                'type' => ['required', 'in:lender'],
                'lender' => ['required', 'string', 'max:150'],
            ],
            default => [
                'type' => ['required', 'in:person,organization,lender'],
            ],
        };
    }

    /**
     * Shape the validated input into the {name, address, payload} triple
     * that Policyholder::create() expects, regardless of which type was submitted.
     */
    public function toPolicyholderAttributes(): array
    {
        $data = $this->validated();

        return match ($data['type']) {
            'person' => [
                'name' => trim("{$data['first_name']} {$data['middle_name']} {$data['last_name']} {$data['suffix']}"),
                'address' => collect([$data['street'], $data['barangay'], $data['city'], $data['province'], $data['region']])
                    ->filter()
                    ->implode(', '),
                'payload' => $data,
            ],
            'organization' => [
                'name' => $data['name'],
                'address' => collect([$data['address_line_1'], $data['barangay'], $data['city'], $data['province'], $data['region']])
                    ->filter()
                    ->implode(', '),
                'payload' => [...$data, 'country' => 'Philippines'],
            ],
            'lender' => [
                'name' => $data['lender'],
                'address' => null,
                'payload' => $data,
            ],
        };
    }
}
