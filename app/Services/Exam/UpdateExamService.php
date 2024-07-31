<?php

namespace App\Services\Exam;

use App\Models\Exam;

class UpdateExamService
{
    public function run(Exam $exam, array $data): void
    {
        $exam->update($data);
    }
}
