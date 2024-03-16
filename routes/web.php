<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ThresholdController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ItemLogsReportController;
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
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return redirect('/admin');
});

Route::get('reports', function () {
    return view('Reports');
})->name('reports');

Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::view('admin', 'Admin')->name('admin');
    Route::view('inventory', 'Inventory')->name('inventory');
    Route::view('products', 'Products')->name('products');
    Route::view('transactions', 'Transactions')->name('transactions');
    Route::view('customers', 'Customers')->name('customers');
    Route::view('sales-reports', 'SalesReports')->name('sales-reports');
    Route::view('inventory-reports', 'InventoryReports')->name('inventory-reports');
    Route::view('settings', 'Settings')->name('settings');
    Route::get('/users', [UserController::class, 'showUsers'])->name('users'); // Users route accessible only to admins
    Route::get('/users', [UserController::class, 'showUsers'])->middleware(['auth', 'verified'])->name('users');
   
});

// Inventory routes
Route::middleware(['auth', 'verified', 'inventory'])->group(function () {
    Route::view('admin', 'Admin')->name('admin');
    Route::view('inventory', 'Inventory')->name('inventory');
    Route::view('products', 'Products')->name('products');
    Route::view('inventory-reports', 'InventoryReports')->name('inventory-reports');
    Route::view('settings', 'Settings')->name('settings');
});


// Cashier routes
Route::middleware(['auth', 'verified', 'cashier'])->group(function () {
    Route::view('admin', 'Admin')->name('admin');
    Route::view('products', 'Products')->name('products');
    Route::view('transactions', 'Transactions')->name('transactions');
    Route::view('customers', 'Customers')->name('customers');
    Route::view('sales-reports', 'SalesReports')->name('sales-reports');
    Route::view('settings', 'Settings')->name('settings');
});

// Use UserController to handle inventory-related functionality

// Notifs 

Route::get('/check-notifications', [NotificationController::class, 'checkNotifications'])->name('check.notifications');

Route::post('/add-user', [UserController::class, 'addUser'])->name('user.add');
Route::get('/edit-user/{id}', [UserController::class, 'editUser'])->name('user.edit');
Route::delete('/delete-user/{id}', [UserController::class, 'deleteUser'])->name('user.delete');
Route::put('/update-user/{id}', [UserController::class, 'updateUser']);


// Use ProductController to handle inventory-related functionality

Route::get('/inventory', [ProductController::class, 'index'])->name('inventory.index');
Route::post('/add-product', [ProductController::class, 'addProduct'])->name('product.add');
Route::put('/update-qty/{id}', [ProductController::class, 'updateQty']);
Route::get('/edit-product/{id}', [ProductController::class, 'editProduct'])->name('product.edit');
Route::delete('/delete-product/{id}', [ProductController::class, 'deleteProduct'])->name('product.delete');
Route::put('/update-product/{id}', [ProductController::class, 'updateProduct']);
Route::get('/products', [ProductsController::class, 'index'])->name('products.index');
Route::post('/upload-inventory', [ProductController::class, 'uploadInventory']);

// Use SettingsController to handle brand/category related functionality

Route::post('/brands', [BrandController::class, 'addBrand'])->name('brands.add');
Route::post('/categories', [CategoryController::class, 'addCategory'])->name('categories.add');
Route::get('/threshold', [ThresholdController::class, 'getThreshold']);
Route::post('/threshold/update', [ThresholdController::class, 'updateThreshold']);
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
Route::post('/update-profile/{id}', [SettingsController::class, 'updateProfile'])->name('update.profile');
Route::get('/avatars/{filename}', [SettingsController::class, 'getAvatar'])->name('avatar');
Route::post('/change-password', [SettingsController::class, 'changePassword'])->name('change.password');


// Use CustomerController to handle customer-related functionality

Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::post('/add-customer', [CustomerController::class, 'addCustomer'])->name('customer.add');
Route::get('/edit-customer/{id}', [CustomerController::class, 'editCustomer'])->name('customer.edit');
Route::delete('/delete-customer/{id}', [CustomerController::class, 'deleteCustomer'])->name('customer.delete');
Route::put('/update-customer/{id}', [CustomerController::class, 'updateCustomer']);

Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
Route::get('/sales-reports', [TransactionController::class, 'salesrep'])->name('sales-reports.index');
Route::get('/item-logs', [ItemLogsReportController::class, 'index'])->name('item-logs.index');
Route::get('/inventory-reports', [ProductsController::class, 'invreport'])->name('inventory-reports.index');

Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::post('/add-transaction', [TransactionController::class, 'addTransaction'])->name('transactions.add');
Route::get('/edit-transaction/{transaction_id}', [TransactionController::class, 'editTransaction'])->name('transactions.edit');
Route::delete('/delete-transaction/{transaction_id}', [TransactionController::class, 'deleteTransaction'])->name('transactions.delete');
Route::put('/update-transaction/{transaction_id}', [TransactionController::class, 'updateTransaction']);


Route::get('/get-top-products', [AdminController::class, 'getTopProducts']);
Route::put('/update-top-product/{id}', [AdminController::class, 'updateTopProduct']);

Route::get('/pos1', [ProductsController::class, 'getProducts']);
Route::get('/transaction1', [TransactionController::class, 'getTransactions']);
Route::get('/pos2', [TransactionController::class, 'getLatestTransactionId']);
Route::post('/add-transaction', [POSController::class, 'addTransaction'])->name('transactions.add');
Route::post('/update-product-quantities', [ProductController::class, 'updateQuantities'])->name('update.product.quantities');
Route::get('/pos', [POSController::class, 'showPOS']);
Route::get('/pos/latest-transaction-id', [POSController::class, 'getLatestTransactionId']);
Route::post('/pos/add-transaction', [POSController::class, 'addTransaction']);
Route::get('/pos/edit-transaction/{transaction_id}', [POSController::class, 'editTransaction']);
Route::put('/pos/update-transaction/{transaction_id}', [POSController::class, 'updateTransaction']);
Route::delete('/pos/delete-transaction/{transaction_id}', [POSController::class, 'deleteTransaction']);
Route::post('/update-transactions', 'TransactionController@updateTransactions');
Route::get('/get-product-by-barcode', [ProductController::class, 'getProductByBarcode']);
Route::get('/get-product-by-barcode', [ProductsController::class, 'getProductByBarcode']);

Route::get('/get-customers', [POSController::class, 'getCustomers']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/reports/{type}', [ReportController::class, 'show'])->name('reports.show');
});


require __DIR__.'/auth.php';

