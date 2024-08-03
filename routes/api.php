<?php

use App\Http\Controllers\CitiesController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ManagementClassController;
use App\Http\Controllers\ManagementSchoolController;
use App\Http\Controllers\ManagementStudentController;
use App\Http\Controllers\StudentController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/school', [UserController::class, 'school']);
    });

    Route::prefix('teachers')->name('teachers.')->group(function () {
        Route::get('/{teacher}', [TeacherController::class, 'show']);
        Route::put('/{teacher}', [TeacherController::class, 'update']);
        Route::delete('/{teacher}', [TeacherController::class, 'delete']);
    });

    Route::prefix('management-schools')->name('management-schools.')->group(function () {
        Route::get('/{managementSchool}', [ManagementSchoolController::class, 'show']);
        Route::get('/{managementSchool}/teachers', [ManagementSchoolController::class, 'listTeachers']);
        Route::get('/{managementSchool}/classes/{class}/students', [ManagementSchoolController::class, 'listAvailableStudentsClass']);
        Route::put('/{managementSchool}', [ManagementSchoolController::class, 'update']);
        Route::delete('/{managementSchool}', [ManagementSchoolController::class, 'delete']);
    });

    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/{student}', [ManagementStudentController::class, 'show']);
        Route::post('/', [StudentController::class, 'store']);
        Route::put('/{student}', [StudentController::class, 'update']);
        Route::get('/{student}/classes', [StudentController::class, 'listClasses']);
        Route::get('/{student}/classes/{class}/exams', [StudentController::class, 'getExamsFromClass']);
        Route::get('/{student}/class', [StudentController::class, 'getActiveClass']);
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/{user}/management-schools', [UserController::class, 'showManagementSchools']);
        Route::get('/{user}/teacher', [UserController::class, 'showTeacher']);
    });

    Route::prefix('classes')->name('classes.')->group(function () {
        Route::get('/{classModel}', [ManagementClassController::class, 'show']);
        Route::post('/{classModel}/close', [ManagementClassController::class, 'close']);
        Route::get('/{classModel}/students', [ManagementClassController::class, 'listStudents']);
        Route::post('/{classModel}/students/{student}', [ManagementClassController::class, 'attachStudent']);
        Route::delete('/{classModel}/students/{student}', [ManagementClassController::class, 'detachStudent']);
    });

    Route::prefix('cities')->name('cities.')->group(function () {
        Route::get('/', [CitiesController::class, 'index']);
        Route::post('/', [CitiesController::class, 'store']);
        Route::delete('/{city}', [CitiesController::class, 'delete']);
    });

    Route::prefix('exams')->name('exams.')->group(function () {
        Route::post('/', [ExamController::class, 'store']);
        Route::put('/{exam}', [ExamController::class, 'update']);
        Route::delete('/{exam}', [ExamController::class, 'delete']);
    });
});
