<?php
namespace App\Http\Controllers;

use App\Models\ExpanseType;
use App\Models\IncomeType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class IncomeExpanseController extends Controller
{
    // Display the expanse index view
    public function expanse_index()
    {
        return Inertia::render('Expanse/Index');
    }
    public function income_index()
    {
        return Inertia::render('Income/Index');
    }

    // Fetch all expanse entries
    public function fetch($process)
    { 
        if ($process == 'Expanse') {
            $record = ExpanseType::all();

        } else {
            $record = IncomeType::all();

        }
        return $record;
    }

    // Store or update a expanse entry
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',

        ]);

     
    
        if ($request->id) {
            if ($request->process == 'Expanse') {
                $record = ExpanseType::findOrFail($request->id);

            } else {
                $record = IncomeType::findOrFail($request->id);

            }
        } else {
            if ($request->process == 'Expanse') {
                $record = new ExpanseType;

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

    // Display a specific expanse entry
    public function show($id, $process)
    {  
        if ($process == 'Expanse') {

            $record = ExpanseType::findOrFail($id);
        } else {
            $record = IncomeType::findOrFail($id);
        }

        return $record;
    }

    // Delete a specific expanse entry
    public function delete($id, $process)
    {
        if ($process == 'Expanse') {
            $record = ExpanseType::findOrFail($id);

        } else {
            $record = IncomeType::findOrFail($id);

        }
        $record->delete();
        return 'success';
    }

    public function pluckExpanses()
    {
        $expanse = ExpanseType::pluck('name', 'id');
        return $expanse;
    }
    public function pluckIncome()
    {
        $income = IncomeType::pluck('name', 'id');
        return $income;
    }
}
