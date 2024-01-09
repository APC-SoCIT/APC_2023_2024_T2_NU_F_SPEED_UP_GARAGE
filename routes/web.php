<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

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

Route::get('/Users', function () {
    return view('Users');
})->middleware(['auth', 'verified'])->name('Users');

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

Route::get('users', function () {
    return view('Users');
})->name('users');

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


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/reports/{type}', [ReportController::class, 'show'])->name('reports.show');
});

require __DIR__.'/auth.php';
