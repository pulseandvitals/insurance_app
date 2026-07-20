<?php

namespace App\Http\Requests\Policyholder;

use App\Models\MotorQuote;
use App\Models\Policyholder;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Base request for policyholder actions nested under a {motorQuote}. Also
 * confirms a bound {policyholder} (destroy/use-address) actually belongs to
 * that motor quote, so one producer can't reach into another's records by
 * guessing IDs.
 */
class PolicyholderRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var MotorQuote|null $motorQuote */
        $motorQuote = $this->route('motorQuote');

        if (! $motorQuote || $this->user()->producer?->id !== $motorQuote->producer_id) {
            return false;
        }

        /** @var Policyholder|null $policyholder */
        $policyholder = $this->route('policyholder');

        return ! $policyholder || $policyholder->motor_quote_id === $motorQuote->id;
    }

    public function rules(): array
    {
        return [];
    }
}
