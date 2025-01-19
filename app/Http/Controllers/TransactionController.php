<?php

namespace App\Http\Controllers;

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
        $transactions = Transaction::all();
        return $transactions;
    }

    // Store or update a transaction entry
    public function store(Request $request)
    {  
        $request->validate([
            'notes' => 'required|string|max:255',
            'type' => 'required',
            'cash_in' => 'nullable|numeric|required_without:cash_out', // Cash In is required if Cash Out is not present
            'cash_out' => 'nullable|numeric|required_without:cash_in', // Cash Out is required if Cash In is not present
             
            'date' => 'required|date',
          
        ]);
        

        // Check if updating or creating a new transaction entry
        if ($request->id) {
            $transaction = Transaction::findOrFail($request->id);
        } else {
            $transaction = new Transaction;
            $transaction->id = Str::orderedUuid();  // Generate UUID for new record
        }

        // Set values
        $transaction->notes = $request->notes;
        $transaction->type = $request->type;
        $transaction->cash_in = $request->cash_in;
        $transaction->cash_out = $request->cash_out;
        $transaction->balance = $request->balance;
        $transaction->date = $request->date;
        $transaction->status = $request->status;

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
