<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Verifies that a "base rate" as defined exists in the array of rates
 * - specified by giving it an exchange rate of 1
 */
class BaseRateExists implements Rule
{
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

            if (doubleval($rateElements[1]) == 1) {
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
        return 'Incorrect rate(s) provided!';
    }
}
