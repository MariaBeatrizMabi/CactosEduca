<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactsController;

use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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
Route::post('/login', 'App\Http\Controllers\LoginController@Auth')->name('Auth');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/ContactUs', [ContactsController::class, 'index'])->name('ContactUs');
