<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
 
use App\Http\Controllers\UserController;
use App\Http\Controllers\MineController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\OrdersController;
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
    
 
// mines
    Route::get('/mines', function(){
        return Inertia::render('Mines/Index');
    })->name('mines');
// customer
    
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
    Route::get('/customer/details/{id}', [CustomerController::class, 'details'])->name('customer.details');
// suppliers
   
    Route::get('/suppliers', [SuppliersController::class, 'index'])->name('suppliers');
    Route::get('/supplier/details/{id}', [SuppliersController::class, 'details'])->name('supplier.details');
// orders
     
    Route::get('/orders', [OrdersController::class, 'index'])->name('orders');
    Route::get('/order/details/{id}', [OrdersController::class, 'details'])->name('order.details');
    
    // orders
    Route::get('/trips', function(){
        return Inertia::render('Trip/Index');
    })->name('trips');
    
    
});
