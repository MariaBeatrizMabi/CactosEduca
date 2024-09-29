<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ContactsController;
use App\Http\Controllers\SchoolDetails;
use App\Http\Controllers\StudentController;
use App\Models\Cities;
use App\Models\Location;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;
use Illuminate\Support\Facades\Auth;



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

    Route::get('/schoolDetails/{cityId}/{schoolNames}', [SchoolDetails::class, 'indexMultipleSchools'])
    ->where(['cityId' => '[0-9]+', 'schoolNames' => '[\pL\s,]+'])
    ->name('SchoolDetailsByCityAndSchools');

    Route::get('/schoolDetails/all', [SchoolDetails::class, 'indexAll'])
    ->name('SchoolDetailsAll');

    Route::get('/schoolDetails/{city}/all', 'App\Http\Controllers\ManagementSchoolController@getSchoolsByCityName')
    ->name('management_school.getSchoolsByCityName');

    Route::get('/schoolDetails/{city}/{schoolName}/{schoolId}', 'App\Http\Controllers\SchoolDetails@indexSchoolDetailsByCityAndSchoolView')
    ->name('SchoolDetailsByCityAndSchool');

    Route::get('/schoolDetails/json/{city}/{schoolName}/{schoolId}', [SchoolDetails::class, 'getSchoolDetails'])
    ->name('school_details.SchoolDetailsJson');

    Route::get('/ManagementSchool/{city_id}/all', [SchoolDetails::class, 'indexAllByCity'])
    ->where(['city_id' => '[0-9]+'])
    ->name('SchoolDetailsAllByCity');
    
    Route::get('/Poll', 'App\Http\Controllers\PollController@all')->name('poll.index');
    Route::post('/PollCreate', 'App\Http\Controllers\PollController@create')->name('poll.create');
    
    Route::get('/ManagementSchool', 'App\Http\Controllers\ManagementSchoolController@index')->name('management_school.index');
    Route::get('/ManagementSchool/all', 'App\Http\Controllers\ManagementSchoolController@examsAll')->name('management_school.examsAll');
    Route::post('/ManagementSchoolCreate', 'App\Http\Controllers\ManagementSchoolController@create')->name('management_school.create');
    Route::put('/ManagementSchool/{managementSchool}', 'App\Http\Controllers\ManagementSchoolController@update')->name('management_school.update');
    Route::get('/ManagementSchool/{managementSchool}', 'App\Http\Controllers\ManagementSchoolController@show')->name('management_school.show');
    Route::delete('/ManagementSchool/{managementSchool}', 'App\Http\Controllers\ManagementSchoolController@delete')->name('management_school.delete');
    
    Route::get('/Teachers', 'App\Http\Controllers\TeacherController@all')->name('teacher.all');
    Route::get('/TeachersSchool', 'App\Http\Controllers\TeacherController@index')->name('teacher.index');
    Route::post('/TeacherCreate', 'App\Http\Controllers\TeacherController@create')->name('teacher.create');
    Route::put('/Teachers/{id}', 'App\Http\Controllers\TeacherController@update')->name('teacher.update');
    Route::delete('/TeachersDeleted/{id}', 'App\Http\Controllers\TeacherController@delete')->name('teacher.delete');

    Route::get('/ClassAllSchool', 'App\Http\Controllers\ManagementClassController@all')->name('class.all');
    Route::get('/ClassSchool', 'App\Http\Controllers\ManagementClassController@index')->name('class.index');
    Route::post('/ClassSchoolCreate', 'App\Http\Controllers\ManagementClassController@create')->name('class.create');
    Route::put('/ClassSchoolUpdate/{id}', 'App\Http\Controllers\ManagementClassController@update')->name('class.update');
    Route::delete('/ClassSchoolDelete/{id}', 'App\Http\Controllers\ManagementClassController@delete')->name('class.delete');

    Route::get('/StudentsAllData', 'App\Http\Controllers\ManagementStudentController@all')->name('student.all');
    Route::get('/StudentsChartData', 'App\Http\Controllers\ManagementStudentController@indexChart')->name('student.indexChart');
    Route::get('/StudentsData', 'App\Http\Controllers\ManagementStudentController@index')->name('student.index');
    Route::post('/StudentCreate', 'App\Http\Controllers\ManagementStudentController@create')->name('student.create');
    Route::delete('/StudentDelete/{id}', 'App\Http\Controllers\ManagementStudentController@delete')->name('student.delete');

    Route::get('/counties', function () {
        return Cities::all();
    });

    Route::get('/locations', function () {
        return Location::all();
    });

    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');

    Route::get('/loginUser', 'App\Http\Controllers\LoginController@GetAuth')->name('GetAuth');

    Route::get('/student/{student}', StudentController::class)->name('student');
    Route::get('/student/{student}/classes/{class}', StudentController::class);
    Route::get('/class/{class}', StudentController::class);
});
