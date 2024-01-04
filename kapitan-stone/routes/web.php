<?php

use Illuminate\Support\Facades\Route;

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
Route::view('admin','Admin');
Route::view('inventory','Inventory');
Route::view('stocks','Stocks');
Route::view('products','Products');
Route::view('reports','Reports');
Route::view('pos','POS');
Route::view('users','Users');
Route::view('settings','Settings');
Route::view('welcome','Welcome');


use App\Http\Controllers\AuthController;

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

