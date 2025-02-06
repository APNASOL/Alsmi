<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
 
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\IncomeExpenseController;
use App\Http\Controllers\IncomeStatementController;
use App\Http\Controllers\ReportController;
 
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */
Route::get('/', [HomeController::class, 'index'])->name('/');
Route::get('/login', [HomeController::class, 'index'])->name('login');
Route::middleware(['web'])->group(function () {

    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::get('/users', [UserController::class, 'index'])->name('users');
    // Admissions

    
    //  Cashbook
    Route::get('/transactions', [TransactionController::class, 'Index'])->name('transactions');
    //  Banks
    Route::get('/banks', [BankController::class, 'Index'])->name('banks');
    //  Accounts 
    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts'); 
    //  Accounts 
    Route::get('/expense/types', [IncomeExpenseController::class, 'expense_index'])->name('expense.types'); 
    Route::get('/income/types', [IncomeExpenseController::class, 'income_index'])->name('income.types'); 
    //  reports 
    Route::get('/reports', [ReportController::class, 'index'])->name('reports'); 
    //  reports 
    Route::get('/income-statements', [IncomeStatementController::class, 'index'])->name('income-statements'); 
   
 

    // data saving routes
    Route::post('/download-pdf',[TransactionController::class,'transactions_pdf_download'])->name('download-pdf');
    Route::post('/download-excel',[TransactionController::class,'transactions_pdf_excel'])->name('download-excel');

});





