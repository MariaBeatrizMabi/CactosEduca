<?php

namespace App\Http\Requests\Exam;

use App\Models\LiteracyParameterValue;
use Illuminate\Foundation\Http\FormRequest;

class StoreExamRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'class_id' => ['required', 'exists:class,id'],
            'student_id' => ['required', 'exists:students,id'],
            'writing' => ['required', 'in:pre_syllabic,syllabic,alphabetical_syllabic,alphabetical,missed,transferred'],
            'reading' => ['required', 'in:not_reader,syllable_reader,word_reader,sentence_reader,fluent_text_reader,
            no_fluent_text_reader,missed,transferred'],
            'action' => ['nullable', 'string'],
            'poll_id' => ['required', 'exists:poll,id'],
            'literacy_parameters_values' => ['required', 'array']
        ];
    }

    protected function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            foreach ($this->literacy_parameters_values as $parametersValue){
                $exists = LiteracyParameterValue::find($parametersValue);
                if(!$exists){
                    $validator->errors()->add('literacy_parameters_values', 'Parâmetro inexistente informado');
                }
            }
        });
    }

}
