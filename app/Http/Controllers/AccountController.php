<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\Bank;
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

    
}
