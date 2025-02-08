<?php
namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionReportExport;

class ReportController extends Controller
{
    public function index()
    {
        return Inertia::render('Report/Index');
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

            // Add cash_in and cash_out
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

        // Calculate overall balance
        $totalBalance = $totalIncome - $totalExpense;

        return response()->json([
            'transactionEntries' => array_values($groupedData),
            'totalIncome'        => $totalIncome,
            'totalExpense'       => $totalExpense,
            'totalBalance'       => $totalBalance,
        ]);
    }

    public function report_pdf_download(Request $request)
    {
        $query = Transaction::query();

        // Apply filters
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

        // Fetch transactions
        $transactions = $query->get();

        // Initialize grouped data
        $groupedData  = [];
        $totalIncome  = 0;
        $totalExpense = 0;

        foreach ($transactions as $transaction) {
            $transactionDate = \Carbon\Carbon::parse($transaction->transaction_date);

            if ($request->selectedFilter === 'Yearly') {
                $groupKey = $transactionDate->format('F Y'); // Example: "January 2025"
            } else {
                $groupKey = $transactionDate->format('Y-m-d'); // Example: "2025-01-20"
            }

            if (! isset($groupedData[$groupKey])) {
                $groupedData[$groupKey] = [
                    'date'    => $groupKey,
                    'income'  => 0,
                    'expense' => 0,
                    'balance' => 0,
                ];
            }

            $incomeAmount  = $transaction->cash_in ?? 0;
            $expenseAmount = $transaction->cash_out ?? 0;

            $groupedData[$groupKey]['income'] += $incomeAmount;
            $groupedData[$groupKey]['expense'] += $expenseAmount;
            $groupedData[$groupKey]['balance'] += ($incomeAmount - $expenseAmount);

            $totalIncome += $incomeAmount;
            $totalExpense += $expenseAmount;
        }

        $totalBalance = $totalIncome - $totalExpense;

        // Load the PDF view
        $pdf = Pdf::loadView('report_pdf', [
            'transactions'   => array_values($groupedData),
            'totalIncome'    => $totalIncome,
            'totalExpense'   => $totalExpense,
            'totalBalance'   => $totalBalance,
            'selectedFilter' => $request->selectedFilter,
            'selectedYear'   => $request->selectedYear ?? null,
            'selectedMonth'  => $request->selectedMonth ?? null,
            'startDate'      => $request->startDate ?? null,
            'endDate'        => $request->endDate ?? null,
        ]);

        return $pdf->download('transaction_report.pdf');
    }

    public function report_exportTo_excel(Request $request)
{
    return Excel::download(new TransactionReportExport($request), 'transaction_report.xlsx');
}
}
