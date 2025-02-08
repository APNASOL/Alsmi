<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TransactionReportExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Transaction::query();

        // Apply filters
        if ($this->request->has('selectedFilter') && $this->request->selectedFilter) {
            $filter = $this->request->selectedFilter;

            if ($filter == 'Yearly' && $this->request->has('selectedYear')) {
                $query->whereYear('transaction_date', $this->request->selectedYear);
            } elseif ($filter == 'Monthly' && $this->request->has(['selectedYear', 'selectedMonth'])) {
                $query->whereYear('transaction_date', $this->request->selectedYear)
                    ->whereMonth('transaction_date', $this->request->selectedMonth);
            } elseif ($filter == 'Custom' && $this->request->has(['startDate', 'endDate'])) {
                $query->whereBetween('transaction_date', [$this->request->startDate, $this->request->endDate]);
            }
        }

        $transactions = $query->get();

        $formattedTransactions = collect();
        $totalIncome = 0;
        $totalExpense = 0;

        foreach ($transactions as $transaction) {
            $incomeAmount  = $transaction->cash_in ?? 0;
            $expenseAmount = $transaction->cash_out ?? 0;
            $balance       = $incomeAmount - $expenseAmount;

            $formattedTransactions->push([
                'Date'     => $transaction->transaction_date,
                'Income'   => $incomeAmount,
                'Expense'  => $expenseAmount,
                'Balance'  => $balance,
            ]);

            $totalIncome += $incomeAmount;
            $totalExpense += $expenseAmount;
        }

        // Add total row
        $formattedTransactions->push([
            'Date'     => 'Total',
            'Income'   => $totalIncome,
            'Expense'  => $totalExpense,
            'Balance'  => $totalIncome - $totalExpense,
        ]);

        return $formattedTransactions;
    }

    public function headings(): array
    {
        return ['Date', 'Income', 'Expense', 'Balance'];
    }
}

