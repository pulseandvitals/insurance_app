<?php

namespace App\Http\Controllers;

use App\Models\MotorQuote;
use App\Models\Policyholder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PolicyholderController extends Controller
{
    public function store(Request $request, MotorQuote $motorQuote): RedirectResponse
    {
        $this->authorizeQuote($request, $motorQuote);

        $type = $request->validate(['type' => ['required', 'in:person,organization,lender']])['type'];

        match ($type) {
            'person' => $this->storePerson($request, $motorQuote),
            'organization' => $this->storeOrganization($request, $motorQuote),
            'lender' => $this->storeLender($request, $motorQuote),
        };

        return back()->with('success', 'Policyholder added.');
    }

    public function destroy(Request $request, MotorQuote $motorQuote, Policyholder $policyholder): RedirectResponse
    {
        $this->authorizeQuote($request, $motorQuote);
        abort_unless($policyholder->motor_quote_id === $motorQuote->id, 403);

        $policyholder->delete();

        return back()->with('success', 'Policyholder removed.');
    }

    public function useAddress(Request $request, MotorQuote $motorQuote, Policyholder $policyholder): RedirectResponse
    {
        $this->authorizeQuote($request, $motorQuote);
        abort_unless($policyholder->motor_quote_id === $motorQuote->id, 403);

        $motorQuote->policyholders()->update(['use_as_address' => false]);
        $policyholder->update(['use_as_address' => true]);

        return back();
    }

    private function storePerson(Request $request, MotorQuote $motorQuote): void
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:10'],
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'suffix' => ['nullable', 'string', 'max:10'],
            'birth_month' => ['nullable', 'string', 'max:20'],
            'birth_day' => ['nullable', 'string', 'max:2'],
            'birth_year' => ['nullable', 'string', 'max:4'],
            'tin' => ['nullable', 'string', 'max:20'],
            'street' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'region' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'barangay' => ['nullable', 'string', 'max:100'],
            'contact_number' => ['required', 'string', 'max:15'],
            'consent' => ['accepted'],
        ]);

        $name = trim("{$data['first_name']} {$data['middle_name']} {$data['last_name']} {$data['suffix']}");
        $address = collect([$data['street'], $data['barangay'], $data['city'], $data['province'], $data['region']])
            ->filter()
            ->implode(', ');

        Policyholder::create([
            'motor_quote_id' => $motorQuote->id,
            'type' => 'person',
            'name' => $name,
            'address' => $address,
            'use_as_address' => $motorQuote->policyholders()->count() === 0,
            'payload' => $data,
        ]);
    }

    private function storeOrganization(Request $request, MotorQuote $motorQuote): void
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'assignee' => ['nullable', 'string', 'max:150'],
            'tin' => ['nullable', 'string', 'max:20'],
            'address_line_1' => ['required', 'string', 'max:150'],
            'region' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'barangay' => ['required', 'string', 'max:100'],
            'zip_code' => ['nullable', 'string', 'max:10'],
            'email' => ['nullable', 'email', 'max:150'],
            'contact_number' => ['nullable', 'string', 'max:15'],
        ]);

        $address = collect([$data['address_line_1'], $data['barangay'], $data['city'], $data['province'], $data['region']])
            ->filter()
            ->implode(', ');

        Policyholder::create([
            'motor_quote_id' => $motorQuote->id,
            'type' => 'organization',
            'name' => $data['name'],
            'address' => $address,
            'use_as_address' => $motorQuote->policyholders()->count() === 0,
            'payload' => [...$data, 'country' => 'Philippines'],
        ]);
    }

    private function storeLender(Request $request, MotorQuote $motorQuote): void
    {
        $data = $request->validate([
            'lender' => ['required', 'string', 'max:150'],
        ]);

        Policyholder::create([
            'motor_quote_id' => $motorQuote->id,
            'type' => 'lender',
            'name' => $data['lender'],
            'address' => null,
            'use_as_address' => false,
            'payload' => $data,
        ]);
    }

    private function authorizeQuote(Request $request, MotorQuote $motorQuote): void
    {
        abort_unless($request->user()->producer?->id === $motorQuote->producer_id, 403);
    }
}
