<?php

namespace App\Http\Controllers;

use App\Models\ExpenseType;
use App\Models\Expense;
use App\Models\Income;
use App\Models\IncomeType;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Carbon\Carbon;

class IncomeStatementController extends Controller
{
    public function index()
    {
        return Inertia::render('IncomeStatement/Index');
    }
    public function create()
    {
       
    }
}
