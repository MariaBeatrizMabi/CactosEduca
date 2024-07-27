<?php

namespace App\Http\Controllers;

use App\Http\Requests\Student\StoreStudentRequest;
use App\Http\Requests\Student\UpdateStudentRequest;
use App\Models\Student;
use App\Services\Student\StoreStudentService;
use App\Services\Student\UpdateStudentService;

class StudentController extends Controller
{
    public function __invoke()
    {
        return view('student');
    }

    public function store(
        StoreStudentRequest $storeStudentRequest,
        StoreStudentService $storeStudentService
    ): void
    {
        $data = $storeStudentRequest->validated();
        $storeStudentService->run($data);
    }

    public function update(
        UpdateStudentRequest $updateStudentRequest,
        UpdateStudentService $updateStudentService,
        Student $student
    ): void
    {
        $data = $updateStudentRequest->validated();
        $updateStudentService->run($student, $data);
    }
}
