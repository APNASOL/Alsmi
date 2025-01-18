<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CashbookController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\BankController;
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
    Route::get('/cashbook', [CashbookController::class, 'Index'])->name('cashbook');
    //  Banks
    Route::get('/banks', [BankController::class, 'Index'])->name('banks');
    //  Accounts 
    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts'); 
 

});





