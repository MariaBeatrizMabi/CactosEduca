<?php

namespace App\Http\Requests\Exam;

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
        ];
    }
}
