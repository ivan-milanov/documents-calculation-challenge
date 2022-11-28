<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class InvoiceControllerTest extends TestCase
{
    /**
     * @var string
     */
    private string $apiBase = '/api/v1/sumInvoices';
    private string $fileContent = '';
    private array $headers = [
        'Accept' => 'application/json'
    ];

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->fileContent = implode('',file('/var/www/data.csv'));
    }

    /**
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @return void
     */
    public function test_sum_invoices_endpoint_returns_success_with_valid_data(): void
    {
        $response = $this->post(
            $this->apiBase,
            [
                'exchangeRates' => '["EUR:1.000","USD:2","GBP:4"]',
                'outputCurrency' => 'USD',
                'file' => UploadedFile::fake()->createWithContent('data.csv', $this->fileContent)
            ],
            $this->headers
        );

        $response->assertStatus(200);
        $response->assertContent('{"123456789":"1,250.000","987654321":"250.000","123465123":"2,650.000"}');
    }

    /**
     * @return void
     */
    public function test_invalid_currency_provided(): void
    {
        // (EUX instead of EUR)
        $response = $this->post(
            $this->apiBase,
            [
                'exchangeRates' => '["EUX:1.000","USD:2","GBP:4"]',
                'outputCurrency' => 'USD',
                'file' => UploadedFile::fake()->createWithContent('data.csv', $this->fileContent)
            ],
            $this->headers
        );
        $response->assertStatus(422);
    }

    /**
     * @return void
     */
    public function test_base_currency_is_not_provided(): void
    {
        $response = $this->post(
            $this->apiBase,
            [
                'exchangeRates' => '["EUR:123","USD:2","GBP:4"]',
                'outputCurrency' => 'USD',
                'file' => UploadedFile::fake()->createWithContent('data.csv', $this->fileContent)
            ],
            $this->headers
        );
        $response->assertStatus(422);
    }

    /**
     * @return void
     */
    public function test_output_currency_is_not_in_the_exchange_rates(): void
    {
        $response = $this->post(
            $this->apiBase,
            [
                'exchangeRates' => '["EUR:1.000","USD:2","GBP:4"]',
                'outputCurrency' => 'BGN',
                'file' => UploadedFile::fake()->createWithContent('data.csv', $this->fileContent)
            ],
            $this->headers
        );
        $response->assertStatus(422);
    }
}
