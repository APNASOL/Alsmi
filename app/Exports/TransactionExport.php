<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionExport implements FromCollection, WithHeadings, WithMapping
{
    protected $transactions;
    protected $selectedFilter;
    protected $selectedYear;
    protected $selectedMonth;
    protected $startDate;
    protected $endDate;

    public function __construct($transactions, $selectedFilter = null, $selectedYear = null, $selectedMonth = null, $startDate = null, $endDate = null)
    {
        $this->transactions   = $transactions;
        $this->selectedFilter = $selectedFilter;
        $this->selectedYear   = $selectedYear;
        $this->selectedMonth  = $selectedMonth;
        $this->startDate      = $startDate;
        $this->endDate        = $endDate;
    }

    public function collection()
    {
        return $this->transactions;
    }

    public function headings(): array
    {
        // Add the header row with the title
        return [
            ['Alshahama Hospital Peshawar'], // Title of the institution
            [
                'Transaction Report',
                $this->getFilteredTitle(),
            ],
            [],
            [
                'Ref No',
                'Description',
                'Transaction Date',
                'Cash In',
                'Cash Out',
                'Balance',
                'Method',
                'Remarks',
            ],
        ];
    }

    public function map($transaction): array
    {
        static $balance = 0;
        $cashIn         = $transaction->cash_in ?? 0;
        $cashOut        = $transaction->cash_out ?? 0;
        $balance += $cashIn - $cashOut;

        return [

            $transaction->ref_no,
            $transaction->remarks ?? 'N/A',
            \Carbon\Carbon::parse($transaction->transaction_date)->format('d-M-Y'),
            $cashIn ? number_format($cashIn, 2) : '-',
            $cashOut ? number_format($cashOut, 2) : '-',
            number_format($balance, 2),
            $transaction->method,
            $transaction->remarks,
        ];
    }

    public function getFilteredTitle()
    {
        // Determine the filtered title based on the selected filter
        if ($this->selectedFilter == 'Yearly') {
            return 'Year: ' . $this->selectedYear;
        } elseif ($this->selectedFilter == 'Monthly') {
            return 'Month: ' . date('F', mktime(0, 0, 0, $this->selectedMonth, 1)) . ' ' . $this->selectedYear;
        } elseif ($this->selectedFilter == 'Custom') {
            return 'From: ' . $this->startDate . ' to ' . $this->endDate;
        }

        return 'All Transactions';
    }

    public function totalRow(): array
    {
        // Calculate the total cash in, cash out, and balance
        $totalCashIn  = $this->transactions->sum('cash_in');
        $totalCashOut = $this->transactions->sum('cash_out');
        $totalBalance = $this->transactions->reduce(function ($carry, $transaction) {
            return $carry + $transaction->cash_in - $transaction->cash_out;
        }, 0);

        return [
            'Total',
            '',
            '',
            '',
            number_format($totalCashIn, 2),
            number_format($totalCashOut, 2),
            number_format($totalBalance, 2),
            '',
            '',
        ];
    }

    public function footerRow(): array
    {
        return [];
    }

    public function getFooterRow(): array
    {
        return ['Total'];
    }

    // Add a custom method to bind the total row
    public function bindValue()
    {
        return [
            $this->totalRow(),
        ];
    }
}
