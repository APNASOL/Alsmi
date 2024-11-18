<?php

use App\Http\Controllers\BankController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MineController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\TripsController;
use App\Http\Controllers\CashBookController;
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

    // MineController routes
    Route::get('/mine/fetch', [MineController::class, 'mines'])->name('api.mine.fetch');
    Route::post('/mine/store', [MineController::class, 'store'])->name('api.mine.store');
    Route::get('/mine/show/{id}', [MineController::class, 'show'])->name('api.mine.show');
    Route::delete('/mine/delete/{id}', [MineController::class, 'delete'])->name('api.mine.delete');
    Route::get('/mines/pluck/', [MineController::class, 'pluck'])->name('api.mines.pluck');

    // CustomerController routes
    Route::get('/customer/fetch', [CustomerController::class, 'customers'])->name('api.customer.fetch');
    Route::post('/customer/store', [CustomerController::class, 'store'])->name('api.customer.store');
    Route::get('/customer/show/{id}', [CustomerController::class, 'show'])->name('api.customer.show');
    Route::delete('/customer/delete/{id}', [CustomerController::class, 'delete'])->name('api.customer.delete');
    Route::get('/customers/pluck', [CustomerController::class, 'pluck'])->name('api.customers.pluck');
    Route::get('/api/customer/fetch/details/{id}', [CustomerController::class, 'customer_details_fetch'])->name('api.customer.fetch.details');

    // OrdersController routes
    Route::get('/order/fetch', [OrdersController::class, 'orders'])->name('api.order.fetch');
    Route::post('/order/store', [OrdersController::class, 'store'])->name('api.order.store');
    Route::get('/order/show/{id}', [OrdersController::class, 'show'])->name('api.order.show');
    Route::get('/api/order/fetch/details/{id}', [OrdersController::class, 'order_details_fetch'])->name('api.order.fetch.details');
    Route::delete('/order/delete/{id}', [OrdersController::class, 'delete'])->name('api.order.delete');
    Route::get('/orders/pluck', [OrdersController::class, 'pluck'])->name('api.orders.pluck');

// SuppliersController routes
    Route::get('/supplier/fetch', [SuppliersController::class, 'suppliers'])->name('api.supplier.fetch');
    Route::post('/supplier/store', [SuppliersController::class, 'store'])->name('api.supplier.store');
    Route::get('/supplier/show/{id}', [SuppliersController::class, 'show'])->name('api.supplier.show');
    Route::delete('/supplier/delete/{id}', [SuppliersController::class, 'delete'])->name('api.supplier.delete');
    Route::get('/suppliers/pluck', [SuppliersController::class, 'pluck'])->name('api.suppliers.pluck');
    Route::get('/api/supplier/fetch/details/{id}', [SuppliersController::class, 'supplier_details_fetch'])->name('api.supplier.fetch.details');

    // TripsController routes
    Route::get('/trip/fetch', [TripsController::class, 'trips'])->name('api.trip.fetch');
    Route::post('/trip/store', [TripsController::class, 'store'])->name('api.trip.store');
    Route::get('/trip/show/{id}', [TripsController::class, 'show'])->name('api.trip.show');
    Route::delete('/trip/delete/{id}', [TripsController::class, 'delete'])->name('api.trip.delete');
    Route::get('/trips/pluck', [TripsController::class, 'pluck'])->name('api.trips.pluck');

    // Users Api
    Route::get('/users/fetch', [UserController::class, 'users_fetch'])->name('api.users.fetch');
    Route::post('/users/store', [UserController::class, 'store'])->name('api.users.store');
    Route::delete('/users/delete/{id}', [UserController::class, 'delete'])->name('api.users.delete');
    Route::get('/users/show/{id}', [UserController::class, 'show'])->name('api.users.show');
    Route::post('/users/update', [UserController::class, 'users_update'])->name('api.users.update');

    // Bank routes
    Route::get('/', [BankController::class, 'fetch'])->name('api.bank.fetch'); // Fetch all banks
    Route::post('/store', [BankController::class, 'store'])->name('api.bank.store'); // Create or update a bank
    Route::get('/show/{id}', [BankController::class, 'show'])->name('api.bank.show'); // Show a specific bank
    Route::delete('/delete/{id}', [BankController::class, 'delete'])->name('api.bank.delete'); // Delete a specific bank
    Route::get('/pluck', [BankController::class, 'pluck'])->name('api.bank.pluck'); // Fetch bank names and IDs

    // Cashbook routes
    Route::get('/', [CashbookController::class, 'fetch'])->name('api.cashbook.fetch'); // Fetch all cashbook entries
    Route::post('/store', [CashbookController::class, 'store'])->name('api.cashbook.store'); // Create or update a cashbook entry
    Route::get('/show/{id}', [CashbookController::class, 'show'])->name('api.cashbook.show'); // Show a specific cashbook entry
    Route::delete('/delete/{id}', [CashbookController::class, 'destroy'])->name('api.cashbook.delete'); // Delete a specific cashbook entry
    Route::get('/pluck', [CashbookController::class, 'pluck'])->name('api.cashbook.pluck'); // (Optional) Fetch cashbook data for dropdowns or other purposes

});
