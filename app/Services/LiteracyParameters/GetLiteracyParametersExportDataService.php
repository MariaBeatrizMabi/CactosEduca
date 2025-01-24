<?php

namespace App\Services\LiteracyParameters;

use App\Models\Exam;

class GetLiteracyParametersExportDataService
{
    public function run($class, $bimester){

        $exams = $class->exams;

        $studentExams = [];
        $examsToReturn = [];

        foreach ($exams as $exam) {
            $studentExams[$exam->student_id][] = $exam;
        }

        foreach ($studentExams as $studentExam) {

            if(array_key_exists($bimester - 1, $studentExam)){
                $studentExam[$bimester - 1]['literacy_parameters_ids'] = $studentExam[$bimester - 1]
                    ->literacyParameterValues()
                    ->pluck('literacy_parameter_values.id')
                    ->toArray();

                $examsToReturn[$studentExam[0]->student_id] = $studentExam[$bimester - 1];
            }

        }

        return [
            'class' => $class->name,
            'teacher' => $class->teacher->name,
            'school' => $class->school->name,
            'type' => $this->classTypeTranslator($class->type),
            'studentsQuantity' => count($class->students),
            'shift' => $this->classShiftTranslator($class->shift),
            'exams' => $examsToReturn,
        ];
    }

    private function classTypeTranslator($type){
        $translator = [
            'preschool' => 'Infantil',
            'elementary_school' => 'Ensino fundamental 1',
            'middle_school' => 'Ensino fundamental 2'
        ];

        return $translator[$type];
    }

    private function classShiftTranslator($type){
        $translator = [
            'morning' => 'Manhã',
            'afternoon' => 'Tarde',
            'night' => 'Noite'
        ];

        return $translator[$type];
    }
}
