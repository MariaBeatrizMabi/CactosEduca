<?php

namespace App\Services\Student;

use App\Models\Student;

class StoreStudentService
{
    public function __construct(private readonly Student $student) {}

    public function run(array $data): Student
    {
        return $this->student->create($data);
    }
}
