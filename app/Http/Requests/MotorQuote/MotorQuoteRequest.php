<?php

namespace App\Http\Requests\MotorQuote;

use App\Models\MotorQuote;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Base request for any action scoped to a {motorQuote} route-model binding.
 * Covers the read-only steps of the wizard (show, pre-flight, checkout, LTO
 * auth) that carry no extra input of their own — subclasses add rules() for
 * the steps that do.
 */
class MotorQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        /** @var MotorQuote $motorQuote */
        $motorQuote = $this->route('motorQuote');

        return $motorQuote && $this->user()->producer?->id === $motorQuote->producer_id;
    }

    public function rules(): array
    {
        return [];
    }
}
