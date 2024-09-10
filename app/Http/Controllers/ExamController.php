<?php

namespace App\Http\Controllers;

use App\Http\Requests\Exam\UpdateExamRequest;
use App\Http\Requests\StoreExamRequest;
use App\Models\Exam;
use App\Services\Exam\StoreExamService;
use App\Services\Exam\UpdateExamService;

class ExamController extends Controller
{
    public function store(
        StoreExamRequest $storeExamRequest,
        StoreExamService $storeExamService
    ): void
    {
        $data = $storeExamRequest->validated();
        $storeExamService->run($data);
    }

    public function update(
        UpdateExamRequest $updateExamRequest,
        UpdateExamService $updateExamService,
        Exam $exam
    ): void
    {
        $data = $updateExamRequest->validated();
        $updateExamService->run($exam, $data);
    }

    public function delete(Exam $exam): void
    {
        $exam->delete();
    }
}
