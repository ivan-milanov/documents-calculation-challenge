<?php

namespace App\Http\Controllers;

use App\Http\Requests\SumInvoiceRequest;
use App\Services\CurrencyService;
use App\Services\InvoiceService;
use Illuminate\Http\JsonResponse;

class InvoiceController extends Controller
{
    /**
     * @var InvoiceService
     */
    private InvoiceService $invoiceService;

    /**
     * @var CurrencyService
     */
    private CurrencyService $currencyService;

    /**
     * @param InvoiceService $invoiceService
     * @param CurrencyService $currencyService
     */
    public function __construct(InvoiceService $invoiceService, CurrencyService $currencyService)
    {
        $this->invoiceService = $invoiceService;
        $this->currencyService = $currencyService;
    }

    /**
     * @param SumInvoiceRequest $request
     * @return JsonResponse
     */
    public function sumInvoices(SumInvoiceRequest $request): JsonResponse
    {
        // set rates
        $this->currencyService->setCurrencyRates(json_decode($request->get('exchangeRates')));
        $this->currencyService->setOutputCurrency($request->get('outputCurrency'));

        // set data
        $this->invoiceService->setData(
            $this->csvToArray($request->file('file')),
            $request->get('vat')
        );

        $invoicesResult = $this->invoiceService->calculateInvoices();

        // as per requirements, we don't need the data stored
        $this->invoiceService->cleanupDatabase();

        return response()->json(
            array_map(function($value){
                return number_format($value,config('currency.decimal_precision'));
            }, $invoicesResult)
        );
    }

    /**
     * Can be extracted to its own class for separation of concerns
     *
     * @param $csvFile
     * @return array
     */
    private function csvToArray($csvFile): array
    {

        $file_to_read = fopen($csvFile, 'r');

        while (!feof($file_to_read) ) {
            $lines[] = fgetcsv($file_to_read, 1000);
        }

        fclose($file_to_read);
        array_pop($lines);
        return $lines;
    }
}
