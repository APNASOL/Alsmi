<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Bank;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AccountController extends Controller
{ 
    public function index()
    {
        return Inertia::render('Banks/Account');
    }
    public function fetch(Request $request)
{
    $query = BankAccount::query();

    if ($request->filled('month') && $request->filled('year')) {
        // Month and Year filter
        $query->whereMonth('date', $request->month)
              ->whereYear('date', $request->year);
    } elseif ($request->filled('year')) {
        // Year-only filter
        $query->whereYear('date', $request->year);
    }

    if ($request->filled('fromDate') && $request->filled('toDate')) {
        // Date range filter
        $query->whereBetween('date', [$request->fromDate, $request->toDate]);
    }

    $accounts_details = $query->get();

    foreach ($accounts_details as $single_record) {
        if ($single_record->process_type == 'Bank') {
            $bank_record = Bank::find($single_record->consumer_id);
            $single_record->consumer_name = $bank_record->name;
            $single_record->bank_title = $bank_record->title;
            $single_record->bank_account = $bank_record->account;
        }
    }

    return $accounts_details;
}

    public function fetch_consumers($consumer_type)
    {
        if($consumer_type == "Customer")
        {
            $customers = Customer::pluck('name','id');
            return $customers;
        }
        
        if($consumer_type == "Supplier")
        {
            $suppliers = Supplier::pluck('name','id');
            return $suppliers;
        }
        if($consumer_type == "Partner")
        {

        }

    }

    public function accounts_data_save(Request $request)
    {
        $request->validate([
            'consumer' => 'required',
            'consumer_id' => 'required',
            'bank_id' => 'required',
            'transaction_type' => 'required',
            'amount' => 'required|numeric',
            'ref_no' => 'required|string',
            'account' => 'required|string',
            'current_amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        if($request->process_for == 'Cash In'){
        
            // do cash in process here
            
        }
        if($request->process_for == 'Cash Out'){
            // do cash out process here
        }
    }
}
