<?php
namespace App\Http\Controllers;

use App\Exports\TransactionExport;
use App\Models\Expense;
use App\Models\ExpenseType;
use App\Models\Income;
use App\Models\IncomeType;
use App\Models\Transaction;
use App\Models\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Storage;

class TransactionController extends Controller
{
    // Display the transaction index view
    public function index()
    {
        return Inertia::render('Transactions/Index');
    }

    // Fetch all transaction entries
    public function fetch(Request $request)
    {
        // Start the query builder for transactions
        $query = Transaction::query();

        // Apply filters only if request contains filtering parameters
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

        // Fetch all transactions if no filters are applied
        $transactions = $query->orderByDesc('transaction_date')->get();

        foreach ($transactions as $transaction) {
            // Check if the transaction has cash_out
            if ($transaction->cash_out) {
                $expense = Expense::where('transaction_id', $transaction->id)->first();
                if ($expense) {
                    $expense_type              = ExpenseType::where('id', $expense->expense_type_id)->first();
                    $transaction->expense_type = $expense_type ? $expense_type->name . " (Expense)" : "Unknown Expense";
                }
            }
            // Check if the transaction has cash_in
            elseif ($transaction->cash_in) {
                $income = Income::where('transaction_id', $transaction->id)->first();
                if ($income) {
                    $income_type              = IncomeType::where('id', $income->income_type_id)->first();
                    $transaction->income_type = $income_type ? $income_type->name . " (Income)" : "Unknown Income";
                }
            }

            // Format transaction date
            $transaction->transaction_date = Carbon::parse($transaction->transaction_date)->format('j F Y');

            // Handle receipt image
            if ($transaction->receipt_image) {
                $upload                     = Upload::where('id', $transaction->receipt_image)->first();
                $transaction->receipt_image = $upload ? getFileUrl($upload->file_name) : null;
            }
        }
        return $transactions;

    }

    // Store or update a transaction entry
    public function store(Request $request)
    { 
        // Validate the incoming request
        $request->validate([
            'process_type' => 'required',
            'income_type'  => 'required_if:process_type,Income',
            'expense_type' => 'required_if:process_type,Expense',
            'cash_in'      => 'nullable|numeric|required_without:cash_out|required_if:income_type,!null',
            'cash_out'     => 'nullable|numeric|required_without:cash_in|required_if:expense_type,!null',
            'date'         => 'required|date',
            'ref_no'       => 'required|string|max:255',
            'method'       => 'required|string|max:255',
            'remarks'      => 'required|string|max:255',
        ]);
        

        if ($request->id) {
            // Fetch existing transaction
            $transaction = Transaction::findOrFail($request->id);

            // Determine previous process type correctly
            if ($transaction->cash_in > 0) {
                $previousProcessType = "Income";
            } elseif ($transaction->cash_out > 0) {
                $previousProcessType = "Expense";
            } else {
                $previousProcessType = null;
            }

            // Fetch related records
            $expense = Expense::where('transaction_id', $transaction->id)->first();
            $income  = Income::where('transaction_id', $transaction->id)->first();

            // Handle process type change
            if ($previousProcessType !== $request->process_type) {
                if ($previousProcessType === "Income" && $income) {
                    $income->delete(); // Delete old income record
                }
                if ($previousProcessType === "Expense" && $expense) {
                    $expense->delete(); // Delete old expense record
                }

                // Reset cash_in or cash_out in transaction to prevent mixed values
                $transaction->cash_in  = null;
                $transaction->cash_out = null;
            }
        } else {
            // New Transaction
            $transaction     = new Transaction();
            $transaction->id = Str::orderedUuid();
        }

        // Save transaction details
        $transaction->transaction_date = $request->date;
        $transaction->ref_no           = $request->ref_no;
        $transaction->method           = $request->method;
        $transaction->remarks          = $request->remarks;
        $transaction->user_id          = auth()->user()->id;

        // Handle receipt image
        if ($request->receipt_image) {
            if ($transaction->receipt_image) {
                $existingInUploads = Upload::where('id', $transaction->receipt_image)->first();
                if ($existingInUploads) {
                    Storage::delete($existingInUploads->file_name);
                    $existingInUploads->delete();
                }
            }

            // Decode Base64 image
            $data = substr($request->receipt_image, strpos($request->receipt_image, ',') + 1);
            $data = base64_decode($data);

            // Generate unique file name and path
            $image_name = Str::random(40) . '.png';
            $image_path = 'TransactionReceipts/' . $image_name;

            // Store the image
            Storage::disk('public')->put($image_path, $data);

            // Save file details
            $upload                     = new Upload();
            $upload->file_original_name = $image_name;
            $upload->extension          = 'png';
            $upload->type               = 'image/png';
            $upload->file_name          = $image_path;
            $upload->save();

            // Assign uploaded image to transaction
            $transaction->receipt_image = $upload->id;
        }

        $transaction->save();

        // Handle Expense
        if ($request->process_type == 'Expense') {
            $expense = Expense::where('transaction_id', $transaction->id)->first();
            if (! $expense) {
                $expense     = new Expense();
                $expense->id = Str::orderedUuid();
            }
            $expense->transaction_id   = $transaction->id;
            $expense->expense_type_id  = $request->expense_type;
            $expense->transaction_date = $request->date;
            $expense->amount           = $request->cash_out;

            // Ensure transaction reflects the expense correctly
            $transaction->cash_out = $request->cash_out;
            $transaction->cash_in  = null; // Ensure cash_in is reset when switching

            $expense->save();
        }

        // Handle Income
        if ($request->process_type == 'Income') {
            $income = Income::where('transaction_id', $transaction->id)->first();
            if (! $income) {
                $income     = new Income();
                $income->id = Str::orderedUuid();
            }
            $income->transaction_id   = $transaction->id;
            $income->income_type_id   = $request->income_type;
            $income->transaction_date = $request->date;
            $income->amount           = $request->cash_in;

            // Ensure transaction reflects the income correctly
            $transaction->cash_in  = $request->cash_in;
            $transaction->cash_out = null; // Ensure cash_out is reset when switching

            $income->save();
        }

        $transaction->save();

        return 'success';
    }

    // Display a specific transaction entry
    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->receipt_image) {
            $upload                     = Upload::where('id', $transaction->receipt_image)->first();
            $transaction->receipt_image = $upload ? getFileUrl($upload->file_name) : null;
        }

        // Determine process type based on cash_in
        if ($transaction->cash_in && $transaction->cash_in > 0) {
            $income_type               = Income::where('transaction_id', $id)->first();
            $transaction->income_type  = $income_type->income_type_id;
            $transaction->process_type = 'Income';
            $transaction->cash_out = 0;
        }
        if ($transaction->cash_out && $transaction->cash_out > 0) {
            $expense_type              = Expense::where('transaction_id', $id)->first();
            $transaction->expense_type = $expense_type->expense_type_id;
            $transaction->process_type = 'Expense';

            $transaction->cash_in = 0;
        }

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

    public function transactions_pdf_download(Request $request)
    {
        $selectedFilter = $request->selectedFilter;
        $selectedYear   = null;
        $selectedMonth  = null;
        $startDate      = null;
        $endDate        = null;

        $query = Transaction::query();

        // Apply filters only if a filter is selected
        if ($selectedFilter) {
            if ($selectedFilter == 'Yearly') {
                $selectedYear = $request->selectedYear;
                $query->whereYear('transaction_date', $selectedYear);
            } elseif ($selectedFilter == 'Monthly') {
                $selectedMonth = $request->selectedMonth;
                $selectedYear  = $request->selectedYear ?? date('Y');
                $query->whereYear('transaction_date', $selectedYear)
                    ->whereMonth('transaction_date', $selectedMonth);
            } elseif ($selectedFilter == 'Custom') {
                $startDate = $request->startDate;
                $endDate   = $request->endDate;

                $query->whereBetween('transaction_date', [$startDate, $endDate]);
            }
        }

        // Fetch all records if no filter is applied
        $transactions = $query->get();
 
        // Generate the PDF
        $pdf = PDF::loadView('transactions_pdf', compact(
            'transactions',
            'selectedFilter',
            'selectedYear',
            'selectedMonth',
            'startDate',
            'endDate'
        ));

        // Return the PDF as a download
        return $pdf->download('TransactionReport.pdf');
    }

    public function transactions_exportTo_excel(Request $request)
    {
        // Start with a query builder
        $query = Transaction::query();

        // Apply filters only if a filter is selected
        if ($request->has('selectedFilter') && $request->selectedFilter) {
            $filter = $request->selectedFilter;

            if ($filter == 'Yearly' && $request->selectedYear) {
                $query->whereYear('transaction_date', $request->selectedYear);
            } elseif ($filter == 'Monthly' && $request->selectedMonth && $request->selectedYear) {
                $query->whereMonth('transaction_date', $request->selectedMonth)
                    ->whereYear('transaction_date', $request->selectedYear);
            } elseif ($filter == 'Custom' && $request->startDate && $request->endDate) {
                $query->whereBetween('transaction_date', [$request->startDate, $request->endDate]);
            }
        }

        // Fetch all records if no filters are applied
        $transactions = $query->get();

        // Export the transactions to Excel
        return Excel::download(new TransactionExport($transactions), 'transaction_report.xlsx');
    }


    public function destroy($id)
    { 

        $transaction = Transaction::findOrFail($id);
        if ($transaction->receipt_image) {
            $existingInUploads = Upload::where('id', $transaction->receipt_image)->first();
            if ($existingInUploads) {
                Storage::delete($existingInUploads->file_name);
                $existingInUploads->delete();
            }
        }
        $transaction->delete();
        return 'success';

    }

}
