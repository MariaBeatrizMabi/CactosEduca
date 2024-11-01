<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\ManagementSchool;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ManagementStudentController extends Controller
{
    public function __invoke()
    {
        return view('student');
    }

    public function all()
    {
        return Student::all();
    }

    public function index()
    {
        $user = Auth::user();

        if ($user && $user->id && $user->type === 'school') {
            $school = ManagementSchool::where('user_id', $user->id)->first();

            if ($school) {
                $students = Student::where('school_id', $school->id)
                    ->with('classData')
                    ->get();
            } else {
                $students = collect();
            }
        } else if ($user && $user->id && $user->type === 'teacher') {
            $teacher = Teacher::where('user_id', $user->id)->first();

            if ($teacher) {
                $students = Student::where('school_id', $teacher->school_id)
                ->with('classData')
                ->get();
            } else {
                $students = collect();
            }
        } else {
            $students = collect();
        }

        return response()->json($students);
    }

    public function getAllStudentsFromSchool($schoolId){
        return Student::where('school_id', $schoolId)
            ->with('classData')
            ->get();
    }

    public function indexChart()
    {
        $user = Auth::user();
        $school = ManagementSchool::where('user_id', $user->id)->first();
        $teacher = Teacher::where('user_id', $user->id)->first();

        if ($user && $user->id && $user->type === 'school') {
            $schoolIds = [$school->id];
            $classes = ClassModel::whereIn('school_id', $schoolIds)->pluck('id');
            $students = Student::whereHas('classData', function($query) use ($classes) {
                $query->whereIn('class_id', $classes);
            })->with('user')->get();
        } else if ($user && $user->id && $user->type === 'teacher') {
            $teacher = Teacher::where('user_id', $user->id)->first();

            if ($teacher) {
                $students = Student::whereHas('classData', function ($query) use ($teacher) {
                    $query->where('teacher_id', $teacher->id);
                })->with('classData')
                ->get();
            } else {
                $students = collect();
            }
        } else {
            $students = collect();
        }

        return response()->json($students);
    }

    public function show(Student $student)
    {
        return response()->json($student);
    }

    public function update(Request $request, Student $student)
    {
        if ($request->input('group_id')) {
            if (!ClassModel::find($request->input('group_id'))) {
                return response()->json(['message' => 'Turma não existe'], 400);
            }
        }

        $student->update([
            'name' => $request->input('name'),
            'age' => $request->input('age'),
            'comments' => $request->input('comments'),
            'enrollment_date' => $request->input('enrollment_date'),
            'group_id' => $request->input('group_id'),
        ]);

        return response()->json(['message' => 'Escola atualizada com sucesso'], 200);
    }

    public function create(Request $request)
    {
        try {
            $student = Student::create([
                'name' => $request->input('name'),
                'age' => $request->input('age'),
                'enrollment_date' => $request->input('enrollment_date'),
                'school_id' => $request->input('school_id'),
            ]);

            Log::info('Dados da escola:', $student->toArray());

            return response()->json(['message' => 'Aluno criado com sucesso'], 201);
        } catch (\Exception $e) {
            if (isset($user)) {
                $user->delete();
            }

            Log::error('Erro ao criar aluno: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao criar aluno'], 500);
        }
    }

    public function delete(Student $studentData, $id) {
        $studentData = Student::where('id', $id)->firstOrFail();

        if (!$studentData) {
            return response()->json(['message' => 'Professor não encontrado'], 404);
        }

        $studentData->delete();

        Log::info('Turma excluída com sucesso: ' . $studentData);

        return response()->json(['message' => 'Professor e usuário excluídos com sucesso'], 200);
    }
}
