<?php

namespace App\Services\Intervention;

use App\Models\Intervention;
use App\Models\Exam;

class InterventionFetchService
{
    public function getInterventionWritingTypeExam($writing, $studentId, $pollId)
    {
        $exam = Exam::where('student_id', $studentId)
                    ->where('poll_id', $pollId)->first();

        return Intervention::where('evaluation_type', $writing)
                            ->get();
    }
}