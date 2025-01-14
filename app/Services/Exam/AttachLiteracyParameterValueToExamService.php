<?php

namespace App\Services\Exam;

use App\Models\Exam;
use Illuminate\Support\Facades\DB;

class AttachLiteracyParameterValueToExamService
{

    public function run(Exam $exam, array $parameters){

        foreach ($parameters as $parameter){
            DB::table('literacy_parameters_value_exam')->insert([
                'literacy_parameter_id' => $parameter,
                'exam_id' => $exam->id
            ]);
        }

    }

}
