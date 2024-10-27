<?php

namespace App\Services;
use Illuminate\Support\Facades\DB; 
use App\Models\ExamsIntervention; 

class InterventionService
{
    public function saveInterventionsExam($examId, $selectedInterventions)
    {
        $savedData = [];

        foreach ($selectedInterventions as $interventionId) {
            try {
                $savedData[] = ExamsIntervention::firstOrCreate(
                [
                    'exam_id' => $examId, 
                    'intervention_id' => $interventionId,
                ]
            );

            } catch (\Exception $e) {
                dd($e);
            }
        }
        return $savedData;
    }
    public function getExamIdStudent($studentId, $pollId)
    {   
        return DB::table('exams')
            ->where('student_id', $studentId)
            ->where('poll_id', $pollId)
            ->value('id');
    }

}