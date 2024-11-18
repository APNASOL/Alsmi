<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class BankController extends Controller
{
    // Display the bank index view
    public function index()
    {
        return Inertia::render('Banks/Index');
    }

    // Fetch all banks
    public function fetch()
    {
        $banks = Bank::all();
        return $banks;
    }

    // Store or update a bank
    public function store(Request $request)
{
    $request->validate([
        'notes' => 'required|string|max:255',
        'type' => 'required|string|max:255',
        'cash_in' => 'required|numeric',
        'cash_out' => 'required|numeric',
        'balance' => 'nullable|numeric',
        'date' => 'required|date',
        'status' => 'required|string|max:255',
    ]);

    // Calculate the balance if it's not provided
    if (!$request->balance) {
        $request->merge(['balance' => $request->cash_in - $request->cash_out]);
    }

    // Check if updating or creating a new entry
    if ($request->id) {
        $cashbook = CashBook::findOrFail($request->id);
    } else {
        $cashbook = new CashBook;
    }

    $cashbook->notes = $request->notes;
    $cashbook->type = $request->type;
    $cashbook->cash_in = $request->cash_in;
    $cashbook->cash_out = $request->cash_out;
    $cashbook->balance = $request->balance;
    $cashbook->date = $request->date;
    $cashbook->status = $request->status;

    $cashbook->save();

    return response()->json(['success' => true]);
}


    // Display a specific bank
    public function show($id)
    {
        $bank = Bank::select('id', 'name', 'branch', 'date', 'title', 'account', 'current_amount', 'created_at', 'updated_at')
            ->findOrFail($id);

        return $bank;
    }

    // Delete a specific bank
    public function delete($id)
    {
        $bank = Bank::findOrFail($id);
        $bank->delete();

        return 'success';
    }

    // Fetch a list of all bank names and IDs
    public function pluck()
    {
        $banks = Bank::pluck('name', 'id');
        return $banks;
    }
}
