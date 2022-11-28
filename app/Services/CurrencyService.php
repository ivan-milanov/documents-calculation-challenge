<?php namespace App\Services;

class CurrencyService
{
    private array $currencyRates;
//    private $baseCurrency;
    private string $outputCurrency;

    /**
     * Validate currency against ISO codes
     *
     * @param $code
     * @return bool
     */
    public function validateCurrencyCode($code): bool
    {
        return empty(array_diff([$code], config('currency.valid_iso_codes')));
    }

    /**
     * Validate that the provided value doesn't exceed the maximum precision
     *
     * @param $value
     * @return bool
     */
    public function validateMaxPrecision($value): bool
    {
        if (!is_numeric($value)) {
            return false;
        }

        $valueSegments = explode(config('currency.decimal_separator'),$value);

        // if no decimal is provided, nothing to validate
        if (!isset($valueSegments[1])) {
            return true;
        }

        return strlen($valueSegments[1]) <= config('currency.decimal_precision');
    }

    /**
     * TODO:: accept only pre-filtered array, instead of the string that should be exploded
     *
     * @param $rates
     * @return void
     */
    public function setCurrencyRates($rates): void
    {
        $filteredRates = [];
        foreach ($rates as $rate) {
            $rate = explode(':',$rate);
            $filteredRates[$rate[0]] = doubleval($rate[1]);

//            if ($rate[1] == 1) {
//                $this->baseCurrency = $rate[0];
//            }
        }

        $this->currencyRates = $filteredRates;
    }

    /**
     * @param $currency
     * @return void
     */
    public function setOutputCurrency($currency): void
    {
        $this->outputCurrency = $currency;
    }

    /**
     * @param $originCurrency
     * @param $targetCurrency
     * @return float|int
     */
    public function getCurrencyMultiplier($originCurrency, $targetCurrency = null): float|int
    {
        $targetCurrency = $targetCurrency ?? $this->outputCurrency;

        return $this->currencyRates[$originCurrency] / $this->currencyRates[$targetCurrency];
    }
}
