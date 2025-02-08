<?php

namespace App\Http\Controllers;

use App\Models\ExpenseType;
use App\Models\Expense;
use App\Models\Income;
use App\Models\IncomeType;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Inertia\Inertia;
use Carbon\Carbon;
   
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionReportExport;

class IncomeStatementController extends Controller
{
    public function index()
    {
        return Inertia::render('IncomeStatement/Index');
    }
  

public function transactions_reports_fetch(Request $request)
{
 
    $query = Transaction::query();

    // Apply filters based on request
    if ($request->has('selectedFilter') && $request->selectedFilter) {
        $filter = $request->selectedFilter;

        if ($filter == 'Yearly' && $request->has('selectedYear')) {
            $query->whereYear('transaction_date', $request->selectedYear);
        } elseif ($filter == 'Monthly' && $request->has(['selectedYear', 'selectedMonth'])) {
            $query->whereYear('transaction_date', $request->selectedYear)
                ->whereMonth('transaction_date', $request->selectedMonth);
        } elseif ($filter == 'Custom' && $request->has(['startDate', 'endDate'])) {
            $query->whereBetween('transaction_date', [$request->startDate, $request->endDate]);
        }
    }

    // Fetch all transactions
    $transactions = $query->get();
 
    // Initialize grouped data
    $groupedData  = [];
    $totalIncome  = 0;
    $totalExpense = 0;

    foreach ($transactions as $transaction) {
        $transactionDate = Carbon::parse($transaction->transaction_date);

        // Determine the group key (monthly or daily)
        if ($request->selectedFilter === 'Yearly') {
            $groupKey = $transactionDate->format('F Y'); // Example: "January 2025"
        } else {
            $groupKey = $transactionDate->format('Y-m-d'); // Example: "2025-01-20"
        }

        // Initialize group if not exists
        if (! isset($groupedData[$groupKey])) {
            $groupedData[$groupKey] = [
                'date'    => $groupKey,
                'income'  => 0,
                'expense' => 0,
                'balance' => 0,
            ];
        }

        // Add cash_in (Credit) and cash_out (Debit)
        $incomeAmount  = $transaction->cash_in ?? 0;
        $expenseAmount = $transaction->cash_out ?? 0;

        // Update group data
        $groupedData[$groupKey]['income'] += $incomeAmount;
        $groupedData[$groupKey]['expense'] += $expenseAmount;
        $groupedData[$groupKey]['balance'] += ($incomeAmount - $expenseAmount);

        // Update totals
        $totalIncome += $incomeAmount;
        $totalExpense += $expenseAmount;
    }

    // Calculate overall balance and profit
    $totalBalance = $totalIncome - $totalExpense;
    $totalProfit = $totalIncome - $totalExpense; // Profit Calculation

    return response()->json([
        'transactionEntries' => array_values($groupedData),
        'totalIncome'        => $totalIncome,
        'totalExpense'       => $totalExpense,
        'totalProfit'        => $totalProfit, // Send profit in response
        'totalBalance'       => $totalBalance,
    ]);
}
 
public function downloadIncomeStatementPDF(Request $request)
{
    $query = Transaction::query();

    // Apply filters
    if ($request->selectedFilter) {
        if ($request->selectedFilter === 'Yearly' && $request->selectedYear) {
            $query->whereYear('transaction_date', $request->selectedYear);
        } elseif ($request->selectedFilter === 'Monthly' && $request->selectedYear && $request->selectedMonth) {
            $query->whereYear('transaction_date', $request->selectedYear)
                  ->whereMonth('transaction_date', $request->selectedMonth);
        } elseif ($request->selectedFilter === 'Custom' && $request->startDate && $request->endDate) {
            $query->whereBetween('transaction_date', [$request->startDate, $request->endDate]);
        }
    }

    // Fetch transactions
    $transactions = $query->get();
    $groupedData = [];
    $totalIncome = 0;
    $totalExpense = 0;

    foreach ($transactions as $transaction) {
        $transactionDate = Carbon::parse($transaction->transaction_date);

        $groupKey = $request->selectedFilter === 'Yearly'
            ? $transactionDate->format('F Y')
            : $transactionDate->format('Y-m-d');

        if (!isset($groupedData[$groupKey])) {
            $groupedData[$groupKey] = [
                'date' => $groupKey,
                'income' => 0,
                'expense' => 0,
                'balance' => 0,
            ];
        }

        $incomeAmount = $transaction->cash_in ?? 0;
        $expenseAmount = $transaction->cash_out ?? 0;

        $groupedData[$groupKey]['income'] += $incomeAmount;
        $groupedData[$groupKey]['expense'] += $expenseAmount;
        $groupedData[$groupKey]['balance'] += ($incomeAmount - $expenseAmount);

        $totalIncome += $incomeAmount;
        $totalExpense += $expenseAmount;
    }

    $totalBalance = $totalIncome - $totalExpense;

    // Load the PDF view
    $pdf = Pdf::loadView('income-statement', [
        'transactions' => array_values($groupedData),
        'selectedFilter' => $request->selectedFilter,
        'selectedYear' => $request->selectedYear,
        'selectedMonth' => $request->selectedMonth,
        'startDate' => $request->startDate,
        'endDate' => $request->endDate,
        'totalIncome' => $totalIncome,
        'totalExpense' => $totalExpense,
        'totalBalance' => $totalBalance,
    ]);

    return $pdf->download('TransactionReport.pdf');
}

}
