<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TransactionController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/users', function () {
    return view('Users');
})->middleware(['auth', 'verified'])->name('users');

Route::get('/users', [UserController::class, 'showUsers'])
    ->middleware(['auth', 'verified'])
    ->name('users');

Route::get('admin', function () {
    return view('Admin');
})->name('admin');

Route::get('inventory', function () {
    return view('Inventory');
})->name('inventory');

Route::get('transactions', function () {
    return view('Transactions');
})->name('transactions');

Route::get('products', function () {
    return view('Products');
})->name('products');

Route::get('reports', function () {
    return view('Reports');
})->name('reports');

Route::get('pos', function () {
    return view('POS');
})->name('pos');

Route::get('settings', function () {
    return view('Settings');
})->name('settings');

Route::get('inventory-reports', function () {
    return view('Inventory-reports');
})->name('Inventory-reports');

Route::get('sales-reports', function () {
    return view('Sales-reports');
})->name('sales-reports');

Route::get('daily-sales', function () {
    return view('Daily-sales');
})->name('daily-sales');

Route::get('monthly-sales', function () {
    return view('Monthly-sales');
})->name('monthly-sales');

Route::get('daily-inventory', function () {
    return view('Daily-inventory');
})->name('daily-inventory');

Route::get('monthly-inventory', function () {
    return view('Monthly-inventory');
})->name('monthly-inventory');

Route::get('customers', function () {
    return view('Customers');
})->name('customers');


// Use UserController to handle inventory-related functionality


Route::post('/add-user', [UserController::class, 'addUser'])->name('user.add');
Route::get('/edit-user/{id}', [UserController::class, 'editUser'])->name('user.edit');
Route::delete('/delete-user/{id}', [UserController::class, 'deleteUser'])->name('user.delete');
Route::put('/update-user/{id}', [UserController::class, 'updateUser']);


// Use ProductController to handle inventory-related functionality

Route::get('/inventory', [ProductController::class, 'index'])->name('inventory.index');
Route::post('/add-product', [ProductController::class, 'addProduct'])->name('product.add');
Route::get('/edit-product/{id}', [ProductController::class, 'editProduct'])->name('product.edit');
Route::delete('/delete-product/{id}', [ProductController::class, 'deleteProduct'])->name('product.delete');
Route::put('/update-product/{id}', [ProductController::class, 'updateProduct']);

Route::get('/products', [ProductsController::class, 'index'])->name('products.index');

// Use CustomerController to handle customer-related functionality

Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::post('/add-customer', [CustomerController::class, 'addCustomer'])->name('customer.add');
Route::get('/edit-customer/{id}', [CustomerController::class, 'editCustomer'])->name('customer.edit');
Route::delete('/delete-customer/{id}', [CustomerController::class, 'deleteCustomer'])->name('customer.delete');
Route::put('/update-customer/{id}', [CustomerController::class, 'updateCustomer']);

Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');


Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::post('/add-transaction', [TransactionController::class, 'addTransaction'])->name('transactions.add');
Route::get('/edit-transaction/{id}', [TransactionController::class, 'editTransaction'])->name('transactions.edit');
Route::delete('/delete-transaction/{id}', [TransactionController::class, 'deleteTransaction'])->name('transactions.delete');
Route::put('/update-transaction/{id}', [TransactionController::class, 'updateTransaction']);




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/reports/{type}', [ReportController::class, 'show'])->name('reports.show');
});

require __DIR__.'/auth.php';

