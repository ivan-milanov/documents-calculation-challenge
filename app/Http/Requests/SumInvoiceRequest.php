<?php

namespace App\Http\Requests;

use App\Rules\ArrayOfRates;
use App\Rules\BaseRateExists;
use App\Rules\IsPartOfProvidedCurrencies;
use App\Rules\ValidIsoCurrency;
use Illuminate\Foundation\Http\FormRequest;
use JetBrains\PhpStorm\ArrayShape;

class SumInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    #[ArrayShape(['file' => "string", 'exchangeRates' => "array", 'outputCurrency' => "string"])]
    public function rules(): array
    {
        return [
            'file' => 'required',
            'exchangeRates' => ['required', 'string', app(ArrayOfRates::class), app(BaseRateExists::class)],
            'outputCurrency' => ['required', 'string', app(ValidIsoCurrency::class), app(IsPartOfProvidedCurrencies::class)],
        ];
    }
}
