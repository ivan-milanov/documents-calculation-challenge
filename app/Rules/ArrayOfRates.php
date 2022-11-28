<?php

namespace App\Rules;

use App\Services\CurrencyService;
use Illuminate\Contracts\Validation\Rule;

/**
 * Array of rates is considered
 *  - a string beginning with [ and ending with ]
 *  - rates are enclosed with " and separated with ,
 *  - currency code is provided and separated from the value with :
 *  - values are by definition 3 decimal precision characters
 *  - currency codes should be valid (ISO standard used for validation)
 */
class ArrayOfRates implements Rule
{
    private CurrencyService $currencyService;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        foreach (json_decode($value) as $rate) {
            $rateElements = explode(':',$rate);

            if (!preg_match('/^([\w]){3}:(\d+(?:[\.\,]\d*)?)$/i', $rate)) {
                return false;
            }

            if (
                !$this->currencyService->validateCurrencyCode($rateElements[0]) ||
                !$this->currencyService->validateMaxPrecision($rateElements[1])
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Incorrect rate(s) provided!';
    }
}
