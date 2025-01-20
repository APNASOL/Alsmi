<?php
namespace App\Http\Controllers;

use App\Models\ExpanseType;
use App\Models\Expense;
use App\Models\Income;
use App\Models\IncomeType;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class TransactionController extends Controller
{
    // Display the transaction index view
    public function index()
    {
        return Inertia::render('Transactions/Index');
    }

    // Fetch all transaction entries
    public function fetch()
    {
        $transactions = Transaction::all(); // Fetch all transactions

        foreach ($transactions as $transaction) {
            // Check if the transaction has cash_out
            if ($transaction->cash_out) {
                // Fetch related expense data
                $expanse      = Expense::where('transaction_id', $transaction->id)->first();
                $expanse_type = ExpanseType::where('id', $expanse->expense_type_id)->first();

                $transaction->expanse_type = $expanse_type->name." (Expanse)";
            }
            // Check if the transaction has cash_in
            elseif ($transaction->cash_in) {
                // Fetch related income data
                $income      = Income::where('transaction_id', $transaction->id)->first();
                $income_type = IncomeType::where('id', $income->income_type_id)->first();

                $transaction->income_type = $income_type->name." (Income)";

            }
        }

        return $transactions; // Return the transactions with related data
    }

    // Store or update a transaction entry
    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'process_type' => 'required',
            'income_type'  => 'required_if:process_type,Income',
            'expanse_type' => 'required_if:process_type,Expanse',
            'cash_in'      => 'nullable|numeric|required_without:cash_out',
            'cash_out'     => 'nullable|numeric|required_without:cash_in',
            'date'         => 'required|date',
            'ref_no'       => 'required|string|max:255',
            'method'       => 'required|string|max:255',
            'remarks'      => 'required|string|max:255',
        ]);

        // Check if updating or creating a new transaction entry
        if ($request->id) {
            $transaction = Transaction::findOrFail($request->id);
            $expanse     = Expense::where('transaction_id', $request->id)->first();
            $income      = Income::where('transaction_id', $request->id)->first();
        } else {
            $transaction     = new Transaction;
            $transaction->id = Str::orderedUuid(); // Generate UUID for new record

            if ($request->process_type == 'Expanse') {
                $expanse     = new Expense();
                $expanse->id = Str::orderedUuid();
            }

            if ($request->process_type == 'Income') {
                $income     = new Income();
                $income->id = Str::orderedUuid();
            }
        }

        // Save the transaction first
        $transaction->transaction_date = $request->date;
        $transaction->ref_no           = $request->ref_no;
        $transaction->method           = $request->method;
        $transaction->remarks          = $request->remarks;
        $transaction->user_id          = 1;

        $transaction->save(); // Save the transaction first, this will generate the `transaction_id`

        // Handle saving Expanses and Incomes after transaction is saved
        if ($request->process_type == 'Expanse') {
            $expanse->transaction_id   = $transaction->id; // Link the transaction ID
            $expanse->expense_type_id  = $request->expanse_type;
            $expanse->transaction_date = $request->date;
            $expanse->amount           = $request->cash_out;
            $transaction->cash_out     = $request->cash_out;
            $expanse->save();
        }

        if ($request->process_type == 'Income') {
            $income->transaction_id   = $transaction->id;      // Link the transaction ID
            $income->income_type_id   = $request->income_type; // Ensure correct income type is set
            $income->transaction_date = $request->date;
            $income->amount           = $request->cash_in;
            $transaction->cash_in     = $request->cash_in;
            $income->save();
        }

        $transaction->save();
        return 'success';
    }

    // Display a specific transaction entry
    public function show($id)
    {
        $transaction = Transaction::select('id', 'notes', 'type', 'cash_in', 'cash_out', 'balance', 'date', 'status', 'created_at', 'updated_at')
            ->findOrFail($id);

        return $transaction;
    }

    // Delete a specific transaction entry
    public function delete($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return 'success';
    }

    // Fetch a list of all transaction entries with the amount (useful for dropdowns)
    public function pluck()
    {
        $transactions = Transaction::pluck('notes', 'id');
        return $transactions;
    }
}
