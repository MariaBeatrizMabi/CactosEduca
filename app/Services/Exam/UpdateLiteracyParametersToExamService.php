<?php

namespace App\Services\Exam;

use App\Models\Exam;
use Illuminate\Support\Facades\DB;

class UpdateLiteracyParametersToExamService
{
    public function run(Exam $exam, array $parameters){

        foreach ($parameters as $parameter){
            DB::table('literacy_parameters_value_exam')->updateOrInsert([
                'literacy_parameter_id' => $parameter,
                'exam_id' => $exam->id
            ]);
        }

        DB::table('literacy_parameters_value_exam')->where('exam_id', $exam->id)
            ->whereNotIn('literacy_parameter_id', $parameters)
            ->delete();

    }
}
