<?php
namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\Income;
use App\Models\IncomeType;
use App\Models\Transaction;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        return Inertia::render('Dashboard/Index');
    }
    public function create()
    {
        return Inertia::render('Dashboard/Create', [
            'title' => "Create new Events",
        ]);
    }
    public function dashboard_fetch()
    {
        $transactions = Transaction::all(); // Fetch all transactions

        foreach ($transactions as $transaction) {
            // Check if the transaction has cash_out
            if ($transaction->cash_out) {
                // Fetch related expense data
                $expense      = Expense::where('transaction_id', $transaction->id)->first();
                $expense_type = ExpenseType::where('id', $expense->expense_type_id)->first();

                $transaction->expense_type = $expense_type->name . " (Expense)";
            }
            // Check if the transaction has cash_in
            elseif ($transaction->cash_in) {
                // Fetch related income data
                $income      = Income::where('transaction_id', $transaction->id)->first();
                $income_type = IncomeType::where('id', $income->income_type_id)->first();

                $transaction->income_type = $income_type->name . " (Income)";

            }

        }

        return $transactions; // Return the transactions with related data
    }

    public function income_details()
    {
        // Step 1: Get the total income of all types combined
        $total_income = Income::join('transactions', 'transactions.id', '=', 'incomes.transaction_id')
            ->sum('transactions.cash_in');

        // Step 2: Group incomes by income type and sum amounts in one query
        $incomeTypes = Income::join('transactions', 'transactions.id', '=', 'incomes.transaction_id')
            ->selectRaw('incomes.income_type_id, SUM(transactions.cash_in) as total_income_type')
            ->groupBy('incomes.income_type_id')
            ->get();

        // Step 3: Attach income type details and calculate percentages
        $incomeTypes = $incomeTypes->map(function ($incomeType) use ($total_income) {
            $type = IncomeType::find($incomeType->income_type_id);
            return [
                'id'                  => $type->id,
                'name'                => $type->name,
                'total_income_type'   => number_format($incomeType->total_income_type, 2), // Format amount
                'percentage_of_total' => number_format(
                    $total_income > 0
                    ? ($incomeType->total_income_type / $total_income) * 100
                    : 0,
                    2
                ), // Format percentage
            ];

        });

        return response()->json([
            'total_income' => number_format($total_income, 2), 
            'income_types' => $incomeTypes,
        ]);
    }

    public function expense_details()
{
    // Step 1: Get the total expense of all types combined
    $total_expense = Expense::join('transactions', 'transactions.id', '=', 'expenses.transaction_id')
        ->sum('transactions.cash_out');

    // Step 2: Group expenses by expense type and sum amounts in one query
    $expenseTypes = Expense::join('transactions', 'transactions.id', '=', 'expenses.transaction_id')
        ->selectRaw('expenses.expense_type_id, SUM(transactions.cash_out) as total_expense_type')
        ->groupBy('expenses.expense_type_id')
        ->get();

    // Step 3: Attach expense type details and calculate percentages
    $expenseTypes = $expenseTypes->map(function ($expenseType) use ($total_expense) {
        $type = ExpenseType::find($expenseType->expense_type_id);
        return [
            'id'                  => $type->id,
            'name'                => $type->name,
            'total_expense_type'   => number_format($expenseType->total_expense_type, 2), // Format amount
            'percentage_of_total' => number_format(
                $total_expense > 0
                ? ($expenseType->total_expense_type / $total_expense) * 100
                : 0,
                2
            ), // Format percentage
        ];
    });

    return response()->json([
        'total_expense' => number_format($total_expense, 2),
        'expense_types' => $expenseTypes,
    ]);
}


}
