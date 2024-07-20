<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __invoke()
    {
        return view('student');
    }
}
