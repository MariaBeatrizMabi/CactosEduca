<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
     public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'in:male,female'],
            'enrollment' => ['required', 'string'],
            'enrollment_date' => ['required', 'date'],
            'school_id' => ['required', 'exists:management_schools,id'],
        ];
    }
}
