<?php

namespace App\Rules;

use Illuminate\Container\EntryNotFoundException;
use Illuminate\Contracts\Container\CircularDependencyException;
use Illuminate\Contracts\Validation\Rule;

/**
 * Verifies that a "base rate" as defined exists in the array of rates
 * - specified by giving it an exchange rate of 1
 */
class IsPartOfProvidedCurrencies implements Rule
{
    /**
     * @param $attribute
     * @param $value
     * @return bool
     * @throws EntryNotFoundException
     * @throws CircularDependencyException
     */
    public function passes($attribute, $value): bool
    {
        $availableCurrencies = json_decode(request()->get('exchangeRates'));

        foreach ($availableCurrencies as $availableCurrency) {
            $availableCurrency = explode(':',$availableCurrency);
            if ($availableCurrency[0] == $value) {
                return true;
            }
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'The output currency is not part of the provided currency rates!';
    }
}
