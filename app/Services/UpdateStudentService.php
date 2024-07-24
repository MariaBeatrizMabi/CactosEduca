<?php

namespace App\Services;

use App\Models\Student;

class UpdateStudentService
{
    public function run(Student $student, array $data): void
    {
        $student->update($data);
    }
}
