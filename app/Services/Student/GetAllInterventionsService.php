<?php

namespace App\Services\Student;

use App\Models\Exam;
use App\Models\Poll;
use App\Models\Student;

class GetAllInterventionsService
{
    public function run($student){
        $interventions = [];

        $exams = Exam::where('student_id', $student)->get();

        foreach ($exams as $exam) {
            $poll = Poll::find($exam->poll_id);
            $interventions[$poll->poll_number] = $exam->intervention;
        }

        return $interventions;
    }
}
