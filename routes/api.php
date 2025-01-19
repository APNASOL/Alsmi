<?php

 
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IncomeExpanseController;

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
    Route::get('/transaction/fetch', [TransactionController::class, 'fetch'])->name('api.transaction.fetch');             // Fetch all transaction entries
    Route::post('/transaction/store', [TransactionController::class, 'store'])->name('api.transaction.store');            // Create or update a transaction entry
    Route::get('/transaction/show/{id}', [TransactionController::class, 'show'])->name('api.transaction.show');           // Show a specific transaction entry
    Route::delete('/transaction/delete/{id}', [TransactionController::class, 'destroy'])->name('api.transaction.delete'); // Delete a specific transaction entry
    Route::get('/transaction/pluck', [TransactionController::class, 'pluck'])->name('api.transaction.pluck');             // (Optional) Fetch transaction data for dropdowns or other purposes
    
    // /Income Expanse routes
    Route::post('/income/expanse/store', [IncomeExpanseController::class, 'store'])->name('api.income.expanse.store');             // Fetch all transaction entries
    Route::get('/income/expanse/fetch/{process}', [IncomeExpanseController::class, 'fetch'])->name('api.income.expanse.fetch');             // Fetch all transaction entries
    Route::post('/income/expanse/store', [IncomeExpanseController::class, 'store'])->name('api.income.expanse.store');            // Create or update a transaction entry
    Route::get('/income/expanse/show/{id}/{process}', [IncomeExpanseController::class, 'show'])->name('api.income.expanse.show');           // Show a specific transaction entry
    Route::delete('/income/expanse/delete/{id}', [IncomeExpanseController::class, 'destroy'])->name('api.income.expanse.delete'); // Delete a specific transaction entry
    Route::get('/expanse/pluck', [IncomeExpanseController::class, 'pluck'])->name('api.expanse.pluck'); 
    Route::get('/income/pluck', [IncomeExpanseController::class, 'pluckIncome'])->name('api.income.pluck'); 
    Route::get('/expanse/pluck', [IncomeExpanseController::class, 'pluckExpanses'])->name('api.expanse.pluck'); 
});
