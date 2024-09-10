<?php

namespace App\Services\Exam;

use App\Models\Exam;

class StoreExamService
{
    public function __construct(private readonly Exam $exam) {}

    public function run(array $data): Exam
    {
        return $this->exam->create($data);
    }
}
