<?php

 
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncomeExpenseController;
use App\Http\Controllers\ReportController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('login', [HomeController::class, 'login'])->name('api.login');
Route::middleware(['web'])->group(function () {
    Route::post('/logout', [HomeController::class, 'logout_user'])->name('api.logout');
    
    Route::get('/dashbaord/transaction/fetch', [TransactionController::class, 'dashboard_fetch'])->name('api.dashbaord.transaction.fetch');    
             // Fetch all transaction entries
    // Users Api
    Route::get('/users/fetch', [UserController::class, 'users_fetch'])->name('api.users.fetch');
    Route::post('/users/store', [UserController::class, 'store'])->name('api.users.store');
    Route::delete('/users/delete/{id}', [UserController::class, 'delete'])->name('api.users.delete');
    Route::get('/users/show/{id}', [UserController::class, 'show'])->name('api.users.show');
    Route::post('/users/update', [UserController::class, 'users_update'])->name('api.users.update');

                                                                                                    // Bank routes
    Route::get('/bank/fetch', [BankController::class, 'fetch'])->name('api.bank.fetch');            // Fetch all banks
    Route::post('/bank/store', [BankController::class, 'store'])->name('api.bank.store');           // Create or update a bank
    Route::get('/bank/show/{id}', [BankController::class, 'show'])->name('api.bank.show');          // Show a specific bank
    Route::delete('/bank/delete/{id}', [BankController::class, 'delete'])->name('api.bank.delete'); // Delete a specific bank
    Route::get('/bank/pluck', [BankController::class, 'pluck'])->name('api.bank.pluck');            // Fetch bank names and IDs
                                                                                                    // Account routes
    Route::get('/fetch/account/details/', [AccountController::class, 'fetch'])->name('api.fetch.account.details');
    Route::get('/fetch/consumers/{consumer_type}', [AccountController::class, 'fetch_consumers'])->name('api.fetch.consumers');
    Route::post('/account/data/store', [AccountController::class, 'accounts_data_save'])->name('api.account.data.store');

                                                                                                                 // Cashbook routes
    Route::post('/transaction/fetch', [TransactionController::class, 'fetch'])->name('api.transaction.fetch');             // Fetch all transaction entries
    Route::post('/transaction/store', [TransactionController::class, 'store'])->name('api.transaction.store');            // Create or update a transaction entry
    Route::get('/transaction/show/{id}', [TransactionController::class, 'show'])->name('api.transaction.show');           // Show a specific transaction entry
    Route::delete('/transaction/delete/{id}', [TransactionController::class, 'destroy'])->name('api.transaction.delete'); // Delete a specific transaction entry
    Route::get('/transaction/pluck', [TransactionController::class, 'pluck'])->name('api.transaction.pluck');             // (Optional) Fetch transaction data for dropdowns or other purposes
    
    // /Income Expense routes
    Route::post('/income/expense/store', [IncomeExpenseController::class, 'store'])->name('api.income.expense.store');             // Fetch all transaction entries
    Route::get('/income/expense/fetch/{process}', [IncomeExpenseController::class, 'fetch'])->name('api.income.expense.fetch');             // Fetch all transaction entries
    Route::post('/income/expense/store', [IncomeExpenseController::class, 'store'])->name('api.income.expense.store');            // Create or update a transaction entry
    Route::get('/income/expense/show/{id}/{process}', [IncomeExpenseController::class, 'show'])->name('api.income.expense.show');           // Show a specific transaction entry
    Route::delete('/income/expense/delete/{id}/{process}', [IncomeExpenseController::class, 'delete'])->name('api.income.expense.delete'); // Delete a specific transaction entry
    Route::get('/expense/pluck', [IncomeExpenseController::class, 'pluck'])->name('api.expense.pluck'); 
    Route::get('/income/pluck', [IncomeExpenseController::class, 'pluckIncome'])->name('api.income.pluck'); 
    Route::get('/expense/pluck', [IncomeExpenseController::class, 'pluckExpenses'])->name('api.expense.pluck'); 
    
 });
