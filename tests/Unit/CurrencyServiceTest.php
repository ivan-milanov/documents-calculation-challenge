<?php

namespace Tests\Unit;

use App\Services\CurrencyService;
use Tests\TestCase;

class CurrencyServiceTest extends TestCase
{
    /**
     * @return void
     */
    public function test_currency_service_validates_properly(): void
    {
        $service = app(CurrencyService::class);

        $response = $service->validateCurrencyCode('ASD');
        $this->assertEquals(false, $response);

        $response = $service->validateCurrencyCode('AAA');
        $this->assertEquals(false, $response);

        $response = $service->validateCurrencyCode('090a9s78nd098as7d');
        $this->assertEquals(false, $response);

        $response = $service->validateCurrencyCode('!!!');
        $this->assertEquals(false, $response);

        $response = $service->validateCurrencyCode('-1');
        $this->assertEquals(false, $response);

        $response = $service->validateCurrencyCode('ВGN'); // <-- invalid, because B is bulgarian V
        $this->assertEquals(false, $response);
    }

    /**
     * @return void
     */
    public function test_that_currency_service_returns_proper_multipliers(): void
    {
        $service = app(CurrencyService::class);

        $service->setCurrencyRates([
            'EUR:1.000',
            'USD:2',
            'GBP:4',
        ]);
        $service->setOutputCurrency('EUR');

        $response = $service->getCurrencyMultiplier('EUR');
        $this->assertEquals(1, $response);

        $response = $service->getCurrencyMultiplier('USD');
        $this->assertEquals(2, $response);

        $response = $service->getCurrencyMultiplier('GBP');
        $this->assertEquals(4, $response);

        $service->setOutputCurrency('GBP');

        $response = $service->getCurrencyMultiplier('EUR');
        $this->assertEquals(0.25, $response);

        $response = $service->getCurrencyMultiplier('USD');
        $this->assertEquals(0.5, $response);

        $response = $service->getCurrencyMultiplier('GBP');
        $this->assertEquals(1, $response);

        $service->setOutputCurrency('USD');

        $response = $service->getCurrencyMultiplier('EUR');
        $this->assertEquals(0.5, $response);

        $response = $service->getCurrencyMultiplier('USD');
        $this->assertEquals(1, $response);

        $response = $service->getCurrencyMultiplier('GBP');
        $this->assertEquals(2, $response);
    }
}
