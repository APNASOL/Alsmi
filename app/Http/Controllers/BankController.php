<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankAccount;
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
            'name' => 'required|string|max:255',
            'branch' => 'required|string|max:255',
            'title' => 'required',
            'account' => 'required|numeric',
            'current_amount' => 'nullable|numeric',
            'date' => 'required|date',

        ]);

        // Calculate the balance if it's not provided
        if (!$request->balance) {
            $request->merge(['balance' => $request->cash_in - $request->cash_out]);
        }

        // Check if updating or creating a new entry
        if ($request->id) {
            $bank = Bank::findOrFail($request->id);
            $bank_account = BankAccount::where('consumer_id', $request->id)->where('process_type', 'Bank')->first();
            if (!$bank_account) {

                $bank_account = new BankAccount;
                $bank_account->id = Str::orderedUuid();
            }
        } else {
            $bank = new Bank;
            $bank->id = Str::orderedUuid();
            $bank_account = new BankAccount;
            $bank_account->id = Str::orderedUuid();
        }

        $bank->name = $request->name;
        $bank->branch = $request->branch;
        $bank->title = $request->title;
        $bank->account = $request->account;
        $bank->current_amount = $request->current_amount;
        $bank->date = $request->date;
        $bank->save();

        $bank_account->consumer_id = $bank->id;
        $bank_account->process_type = "Bank";
        $bank_account->note = "Bank new entry";
        $bank_account->type = "";
        $bank_account->bank_id = $bank->id;
        $bank_account->ref_no = null;
        $bank_account->cash_in = $request->current_amount;
        $bank_account->cash_out = 0;
        $bank_account->date = $request->date;
        $bank_account->save();

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
