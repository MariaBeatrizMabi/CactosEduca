<?php

use App\Http\Controllers\ManagementSchoolController;
use App\Http\Controllers\ManagementStudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('teachers')->name('teachers.')->group(function () {
    Route::get('/{teacher}', [TeacherController::class, 'show']);
    Route::put('/{teacher}', [TeacherController::class, 'update']);
    Route::delete('/{teacher}', [TeacherController::class, 'delete']);
});

Route::prefix('management-schools')->name('management-schools.')->group(function () {
    Route::get('/{managementSchool}', [ManagementSchoolController::class, 'show']);
    Route::put('/{managementSchool}', [ManagementSchoolController::class, 'update']);
    Route::delete('/{managementSchool}', [ManagementSchoolController::class, 'delete']);
});

Route::prefix('students')->name('students.')->group(function () {
    Route::get('/{student}', [ManagementStudentController::class, 'show']);
    Route::put('/{student}', [ManagementStudentController::class, 'update']);
});

Route::prefix('users')->name('users.')->group(function () {
    Route::get('/{user}/management-schools', [UserController::class, 'showManagementSchools']);
});
