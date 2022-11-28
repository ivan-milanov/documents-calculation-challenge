<?php namespace App\Services;

use App\Models\Document;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Type:
 *  1 = invoice
 *  2 = credit note (subtract from invoice)
 *  3 = debit note (add to invoice)
 */
class InvoiceService
{
    private const CUSTOMER_INDEX = 0;
    private const VAT_INDEX = 1;
    private const DOCUMENT_NUMBER_INDEX = 2;
    private const TYPE_INDEX = 3;
    private const PARENT_DOCUMENT_INDEX = 4;
    private const CURRENCY_INDEX = 5;
    private const TOTAL_INDEX = 6;

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
     * @param array $data
     * @param $filterVat
     * @return bool
     */
    public function setData(array $data, $filterVat = null): bool
    {
        // cleanup header row if exists
        if ($this->isHeaderRow($data[0])) {
            unset($data[0]);
        }

        try {
            DB::beginTransaction();

            foreach ($data as $record) {
                if ($filterVat && $filterVat != $record[self::VAT_INDEX]) {
                    continue;
                }

                if (
                    !$this->currencyService->validateCurrencyCode($record[self::CURRENCY_INDEX]) ||
                    !$this->currencyService->validateMaxPrecision($record[self::TOTAL_INDEX])
                ) {
                    DB::rollBack();
                    return false;
                }

                (new Document)->create([
                    'customer' => $record[self::CUSTOMER_INDEX],
                    'vat_number' => $record[self::VAT_INDEX],
                    'id' => $record[self::DOCUMENT_NUMBER_INDEX],
                    'type' => $record[self::TYPE_INDEX],
                    'parent_document' => $record[self::PARENT_DOCUMENT_INDEX],
                    'currency' => $record[self::CURRENCY_INDEX],
                    'total' => $record[self::TOTAL_INDEX],
                ]);
            }

            DB::commit();
            return true;
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            DB::rollBack();
            return false;
        }
    }

    /**
     * TODO:: debit / credit notes should be extracted from the invoice records (currently this is braking the sOlid principle)
     *
     * @return array
     */
    public function calculateInvoices(): array
    {
        $invoiceRecords = Document::all();

        $invoices = [];
        foreach ($invoiceRecords as $invoiceRecord) {
            if (!isset($invoices[$invoiceRecord->vat_number])) {
                $invoices[$invoiceRecord->vat_number] = 0;
            }

            $currencyIndex = $this->currencyService->getCurrencyMultiplier($invoiceRecord->currency);

            if ($invoiceRecord->type === "1") {
                $invoices[$invoiceRecord->vat_number] += $invoiceRecord->total * $currencyIndex;
            }

            if ($invoiceRecord->type === "2") {
                $invoices[$invoiceRecord->vat_number] -= $invoiceRecord->total * $currencyIndex;
            }

            if ($invoiceRecord->type === "3") {
                $invoices[$invoiceRecord->vat_number] += $invoiceRecord->total * $currencyIndex;
            }
        }

        return $invoices;
    }

    /**
     * @return void
     */
    public function cleanupDatabase(): void
    {
        // cleanup database
        Document::query()->delete();
    }

    /**
     * @param $potentialHeaderRow
     * @return bool
     */
    private function isHeaderRow($potentialHeaderRow): bool
    {
        return $potentialHeaderRow[self::CUSTOMER_INDEX] === 'Customer';
    }
}
