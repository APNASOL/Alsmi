<?php

namespace App\Http\Controllers;

use App\Models\CashBook;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CashBookController extends Controller
{
    // Display the cashbook index view
    public function index()
    {
        return Inertia::render('CashBook/Index');
    }

    // Fetch all cashbook entries
    public function fetch()
    {
        $cashbooks = CashBook::all();
        return $cashbooks;
    }

    // Store or update a cashbook entry
    public function store(Request $request)
    {  
        $request->validate([
            'notes' => 'required|string|max:255',
            'type' => 'required',
            'cash_in' => 'nullable|numeric|required_without:cash_out', // Cash In is required if Cash Out is not present
            'cash_out' => 'nullable|numeric|required_without:cash_in', // Cash Out is required if Cash In is not present
             
            'date' => 'required|date',
          
        ]);
        

        // Check if updating or creating a new cashbook entry
        if ($request->id) {
            $cashbook = CashBook::findOrFail($request->id);
        } else {
            $cashbook = new CashBook;
            $cashbook->id = Str::orderedUuid();  // Generate UUID for new record
        }

        // Set values
        $cashbook->notes = $request->notes;
        $cashbook->type = $request->type;
        $cashbook->cash_in = $request->cash_in;
        $cashbook->cash_out = $request->cash_out;
        $cashbook->balance = $request->balance;
        $cashbook->date = $request->date;
        $cashbook->status = $request->status;

        $cashbook->save();

        return 'success';
    }

    // Display a specific cashbook entry
    public function show($id)
    {
        $cashbook = CashBook::select('id', 'notes', 'type', 'cash_in', 'cash_out', 'balance', 'date', 'status', 'created_at', 'updated_at')
            ->findOrFail($id);

        return $cashbook;
    }

    // Delete a specific cashbook entry
    public function delete($id)
    {
        $cashbook = CashBook::findOrFail($id);
        $cashbook->delete();

        return 'success';
    }

    // Fetch a list of all cashbook entries with the amount (useful for dropdowns)
    public function pluck()
    {
        $cashbooks = CashBook::pluck('notes', 'id');
        return $cashbooks;
    }
}
