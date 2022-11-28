<?php

namespace App\Rules;

use App\Services\CurrencyService;
use Illuminate\Contracts\Validation\Rule;

/**
 * Verifies that a "base rate" as defined exists in the array of rates
 * - specified by giving it an exchange rate of 1
 */
class ValidIsoCurrency implements Rule
{
    /**
     * @var CurrencyService
     */
    private CurrencyService $currencyService;

    /**
     * @param CurrencyService $currencyService
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
        return $this->currencyService->validateCurrencyCode($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Incorrect output currency provided!';
    }
}
