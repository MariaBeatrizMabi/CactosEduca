<?php

namespace App\Services;
use Illuminate\Support\Facades\DB; 
use App\Models\ExamsIntervention; 

class InterventionService
{
    public function saveInterventionsExam($examId, $selectedInterventions)
    {

        foreach ($selectedInterventions as $interventionId) {
            try {
                ExamsIntervention::create([
                    'exam_id' => $examId, 
                    'intervention_id' => $interventionId,
                ]);
            } catch (\Exception $e) {
                dd($e);
            }
        }
    }
    public function getExamIdStudent($studentId, $pollId)
    {   
        return DB::table('exams')
            ->where('student_id', $studentId)
            ->where('poll_id', $pollId)
            ->value('id');
    }

}