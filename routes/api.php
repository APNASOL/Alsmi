<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CashBookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    Route::get('/cashbook/fetch', [CashbookController::class, 'fetch'])->name('api.cashbook.fetch');             // Fetch all cashbook entries
    Route::post('/cashbook/store', [CashbookController::class, 'store'])->name('api.cashbook.store');            // Create or update a cashbook entry
    Route::get('/cashbook/show/{id}', [CashbookController::class, 'show'])->name('api.cashbook.show');           // Show a specific cashbook entry
    Route::delete('/cashbook/delete/{id}', [CashbookController::class, 'destroy'])->name('api.cashbook.delete'); // Delete a specific cashbook entry
    Route::get('/cashbook/pluck', [CashbookController::class, 'pluck'])->name('api.cashbook.pluck');             // (Optional) Fetch cashbook data for dropdowns or other purposes

});
