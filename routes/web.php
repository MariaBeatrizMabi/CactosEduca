<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\SchoolDetails;
use App\Models\County;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use Illuminate\Support\Facades\Auth;

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
})->name('login');

Route::post('/login', 'App\Http\Controllers\LoginController@Auth')->name('Auth');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/ContactUs', [ContactsController::class, 'index'])->name('ContactUs');
    Route::get('/Management', [ContactsController::class, 'index'])->name('management');
    
    Route::get('/schoolDetails', [SchoolDetails::class, 'index'])->name('index');
    Route::get('/Filters', [SchoolDetails::class, 'filteredScreen'])->name('filteredScreen');
    
    Route::get('/schoolDetails/{city}/{schoolName}', [SchoolDetails::class, 'indexFiltered'])
    ->where(['city' => '[\pL0-9\s]+', 'schoolName' => '[a-zA-Z0-9\s]+'])
    ->name('SchoolDetailsByCityAndSchool');

    Route::get('/ManagementSchool', 'App\Http\Controllers\ManagementSchoollController@index')->name('management_school.index');
    Route::post('/ManagementSchoolCreate', 'App\Http\Controllers\ManagementSchoollController@create')->name('management_school.create');
    Route::put('/ManagementSchool/{managementSchooll}', 'App\Http\Controllers\ManagementSchoollController@update')->name('management_school.update');
    Route::get('/ManagementSchool/{managementSchooll}', 'App\Http\Controllers\ManagementSchoollController@show')->name('management_school.show');
    Route::delete('/ManagementSchool/{managementSchooll}', 'App\Http\Controllers\ManagementSchoollController@delete')->name('management_school.delete');

    Route::get('/counties', function () {
        return County::all();
    });

    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');

    Route::get('/loginUser', 'App\Http\Controllers\LoginController@GetAuth')->name('GetAuth');
});
