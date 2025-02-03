<?php
namespace App\Http\Controllers;

use App\Models\ExpenseType;
use App\Models\Expense;
use App\Models\Income;
use App\Models\IncomeType;
use App\Models\Transaction;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

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
                $expense      = Expense::where('transaction_id', $transaction->id)->first();
                $expense_type = ExpenseType::where('id', $expense->expense_type_id)->first();

                $transaction->expense_type = $expense_type->name." (Expense)";
            }
            // Check if the transaction has cash_in
            elseif ($transaction->cash_in) {
                // Fetch related income data
                $income      = Income::where('transaction_id', $transaction->id)->first();
                $income_type = IncomeType::where('id', $income->income_type_id)->first();

                $transaction->income_type = $income_type->name." (Income)";

            } 
            $carbonDate = Carbon::parse($transaction->transaction_date)->format('j F Y');
            $transaction->transaction_date = $carbonDate;
            

            if ($transaction->receipt_image) { 
                // dd(get_storage_url($transaction->receipt_image));
                $Upload = Upload::where('id', $transaction->receipt_image)->first();

                $picture = get_storage_url($Upload->file_name) ?? "";
                $transaction->receipt_image = $picture;
            }
          
        }

        return $transactions; // Return the transactions with related data
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

                $transaction->expense_type = $expense_type->name." (Expense)";
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
            'expense_type' => 'required_if:process_type,Expense',
            'cash_in'      => 'nullable|numeric|required_without:cash_out',
            'cash_out'     => 'nullable|numeric|required_without:cash_in',
            'date'         => 'required|date',
            'ref_no'       => 'required|string|max:255',
            'method'       => 'required|string|max:255',
            'remarks'      => 'required|string|max:255',
            'image' => 'nullable',
        ]);

        // Check if updating or creating a new transaction entry
        if ($request->id) {
            $transaction = Transaction::findOrFail($request->id);
            $expense     = Expense::where('transaction_id', $request->id)->first();
            $income      = Income::where('transaction_id', $request->id)->first();
        } else {
            $transaction     = new Transaction;
            $transaction->id = Str::orderedUuid(); // Generate UUID for new record

            if ($request->process_type == 'Expense') {
                $expense     = new Expense();
                $expense->id = Str::orderedUuid();
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

        // if ($request->receipt_image) {
        //     // Delete existing upload if any
        //     Upload::where('id', $transaction->id)->delete();
        
        //     // Decode the base64 image
        //     $data = substr($request->receipt_image, strpos($request->receipt_image, ',') + 1);
        //     $data = base64_decode($data);
        
        //     // Ensure the directory exists
        //     $directory = 'TransactionReceipts';
        //     if (!Storage::disk('real_public')->exists($directory)) {
        //         Storage::disk('real_public')->makeDirectory($directory);
        //     }
        
        //     // Save the file
        //     $photo_name_with_path = $directory . '/' . Str::random(40) . '.png';
        //     Storage::disk('real_public')->put($photo_name_with_path, $data);
        //     $fileSize = strlen($data);
        
        //     // Save file details in the Uploads table
        //     $Upload = new Upload;
        //     $Upload->file_original_name = $photo_name_with_path;
        //     $Upload->extension = 'png';
        //     $Upload->type = 'image/png';
        //     $Upload->file_size = $fileSize;
        //     $Upload->file_name = $photo_name_with_path;
        //     $Upload->save();
        
        //     // Associate the upload with the transaction
        //     $transaction->receipt_image = $Upload->id;
        // }
        

        $transaction->save(); // Save the transaction first, this will generate the `transaction_id`

        // Handle saving Expenses and Incomes after transaction is saved
        if ($request->process_type == 'Expense') {
            $expense->transaction_id   = $transaction->id; // Link the transaction ID
            $expense->expense_type_id  = $request->expense_type;
            $expense->transaction_date = $request->date;
            $expense->amount           = $request->cash_out;
            $transaction->cash_out     = $request->cash_out;
            $expense->save();
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
