<?php

namespace App\Http\Requests\Intervention;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log; 


class StudentInterventionRequest extends FormRequest
{
    public function authorize()
    {
        return true;  
    }

    public function rules()
    {

        return [
            'selectedInterventions' => 'required|array',
            'selectedInterventions.*' => 'exists:interventions,id',
            'exam_id' => 'required|exists:exams,id', 
        ];
              
    }
}