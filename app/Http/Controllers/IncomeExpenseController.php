<?php
namespace App\Http\Controllers;

use App\Models\ExpenseType;
use App\Models\IncomeType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class IncomeExpenseController extends Controller
{
    // Display the expense index view
    public function expense_index()
    {
        return Inertia::render('Expense/Index');
    }
    public function income_index()
    {
        return Inertia::render('Income/Index');
    }

    // Fetch all expense entries
    public function fetch($process)
    { 
        if ($process == 'Expense') {
            $record = ExpenseType::all();

        } else {
            $record = IncomeType::all();

        }
        return $record;
    }

    // Store or update a expense entry
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',

        ]);

        
        if ($request->id) {
           
            if ($request->process == 'Expense') {
                $record = ExpenseType::findOrFail($request->id);

            } else {
                $record = IncomeType::findOrFail($request->id);

            }
        } else {
            if ($request->process == 'Expense') {
                $record = new ExpenseType;

            } else {

                $record = new IncomeType;
            }
            $record->id = Str::orderedUuid(); // Generate UUID for new record
        }

        // Set values
        $record->name = $request->name;

        // $record->status = 1;

        $record->save();

        return 'success';
    }

    // Display a specific expense entry
    public function show($id, $process)
    {  
        if ($process == 'Expense') {

            $record = ExpenseType::findOrFail($id);
        } else {
            $record = IncomeType::findOrFail($id);
        }

        return $record;
    }

    // Delete a specific expense entry
    public function delete($id, $process)
    {
        if ($process == 'Expense') {
            $record = ExpenseType::findOrFail($id);

        } else {
            $record = IncomeType::findOrFail($id);

        }
        $record->delete();
        return 'success';
    }

    public function pluckExpenses()
    {
        $expense = ExpenseType::pluck('name', 'id');
        return $expense;
    }
    public function pluckIncome()
    {
        $income = IncomeType::pluck('name', 'id');
        return $income;
    }
}
