<?php

namespace App\Http\Controllers;

use App\Http\Requests\Exam\StoreExamRequest;
use App\Http\Requests\Exam\UpdateExamRequest;
use App\Models\Exam;
use App\Services\Exam\StoreExamService;
use App\Services\Exam\UpdateExamService;

class ExamController extends Controller
{
    public function store(
        StoreExamRequest $storeExamRequest,
        StoreExamService $storeExamService
    )
    {
        $data = $storeExamRequest->validated();
        $exam = $storeExamService->run($data);
        return response()->json($exam);
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
