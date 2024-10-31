<?php

use App\Http\Controllers\CitiesController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ManagementClassController;
use App\Http\Controllers\ManagementSchoolController;
use App\Http\Controllers\ManagementStudentController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InterventionController;
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

// Rotas protegidas com autenticação via Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // Rotas relacionadas ao usuário
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/school', [UserController::class, 'school']);
    });

    // Rotas relacionadas aos professores
    Route::prefix('teachers')->name('teachers.')->group(function () {
        Route::get('/{teacher}', [TeacherController::class, 'show']);
        Route::put('/{teacher}', [TeacherController::class, 'update']);
        Route::delete('/{teacher}', [TeacherController::class, 'delete']);
    });

    // Rotas para gerenciamento de escolas
    Route::prefix('management-schools')->name('management-schools.')->group(function () {
        Route::get('/{managementSchool}', [ManagementSchoolController::class, 'show']);
        Route::get('/{managementSchool}/teachers', [ManagementSchoolController::class, 'listTeachers']);
        Route::get('/{school}/classes', [ManagementSchoolController::class, 'listAvailableClassInSchools']);
        Route::get('/{school}/teacher', [ManagementSchoolController::class, 'listAvailableClassForTeacherInSchools']);
        Route::get('/{managementSchool}/classes/{class}/students', [ManagementSchoolController::class, 'listAvailableStudentsClass']);
        Route::put('/{managementSchool}', [ManagementSchoolController::class, 'update']);
        Route::delete('/{managementSchool}', [ManagementSchoolController::class, 'delete']);
    });

    // Rotas relacionadas aos estudantes
    Route::prefix('students')->name('students.')->group(function () {
        Route::get('/{student}', [ManagementStudentController::class, 'show']);
        Route::post('/', [StudentController::class, 'store']);
        Route::put('/{student}', [StudentController::class, 'update']);
        Route::get('/{student}/classes', [StudentController::class, 'listClasses']);
        Route::get('/{student}/classes/{class}/exams', [StudentController::class, 'getExamsFromClass']);
        Route::get('/{student}/class', [StudentController::class, 'getActiveClass']);
    });

    // Rotas relacionadas aos usuários
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/{user}/management-schools', [UserController::class, 'showManagementSchools']);
        Route::get('/{user}/teacher', [UserController::class, 'showTeacher']);
    });

    // Rotas relacionadas às turmas
    Route::prefix('classes')->name('classes.')->group(function () {
        Route::get('/{classModel}', [ManagementClassController::class, 'show']);
        Route::post('/{classModel}/close', [ManagementClassController::class, 'close']);
        Route::get('/{classModel}/students', [ManagementClassController::class, 'listStudents']);
        Route::post('/{classModel}/students/{student}', [ManagementClassController::class, 'attachStudent']);
        Route::delete('/{classModel}/students/{student}', [ManagementClassController::class, 'detachStudent']);
        Route::get('/{classId}/exams', [ManagementClassController::class, 'examClass']);
    });

    // Rotas relacionadas às cidades
    Route::prefix('cities')->name('cities.')->group(function () {
        Route::get('/', [CitiesController::class, 'index']);
        Route::post('/', [CitiesController::class, 'store']);
        Route::delete('/{city}', [CitiesController::class, 'delete']);
    });

    Route::prefix('locations')->name('locations')->group(function () {
        Route::post('/', [LocationController::class, 'store']);
    });

    Route::prefix('exams')->name('exams.')->group(function () {
        Route::post('/', [ExamController::class, 'store']);
        Route::put('/{exam}', [ExamController::class, 'update']);
        Route::delete('/{exam}', [ExamController::class, 'delete']);
    });

    Route::get('/interventions/exam/{studentId}/{pollId}', [InterventionController::class, 'getExamId']);
    Route::get('/student/all-interventions/{studentId}', [StudentController::class, 'getAllStudentInterventions']);
    Route::get('/interventions/{writing}/{studentId}/{pollIdD}', [InterventionController::class, 'getInterventions']);
    Route::post('/students/interventions', [InterventionController::class, 'saveStudentInterventions']);
});

Route::get('/{classModel}/monitoring-form', [ManagementClassController::class, 'monitoringForm']);
