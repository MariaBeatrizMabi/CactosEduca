<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExamRequest;
use App\Services\Exam\StoreExamService;

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
}
