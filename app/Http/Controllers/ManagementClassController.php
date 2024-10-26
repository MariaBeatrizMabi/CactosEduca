<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\ManagementSchool;
use App\Models\Student;
use App\Models\Teacher;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ManagementClassController extends Controller
{
    public function all()
    {
        return ClassModel::all();
    }

    public function examClass($classId)
    {
        $class = ClassModel::with(['students.exams'])->findOrFail($classId);

        $studentsWithExams = $class->students->map(function ($student) {
            return [
                'student_name' => $student->name,
                'exams' => $student->exams->map(function ($exam) {
                    return [
                        'reading' => $exam->reading,
                        'writing' => $exam->writing,
                        'action' => $exam->action,
                        'poll_number' => $exam->poll->poll_number
                    ];
                })
            ];
        });

        return response()->json([
            'class' => $class->name,
            'students' => $studentsWithExams
        ]);
    }



    public function index()
    {
        $user = Auth::user();
        $school = ManagementSchool::where('user_id', $user->id)->first();
        $teacher = Teacher::where('user_id', $user->id)->first();

        if ($user && $user->id && $user->type === 'school') {
            $schoolIds = [$school->id];
            $classes = ClassModel::with(['teacher', 'studentsInClass.studentsChart'])
                ->whereIn('school_id', $schoolIds)
                ->get();
        } else if ($user && $user->id && $user->type === 'teacher') {
            $teacherIds = [$teacher->id];
            $classes = ClassModel::with(['teacher', 'studentsInClass.studentsChart'])
                ->where('teacher_id', $teacherIds)
                ->get();
        } else {
            $classes = collect();
        }

        return response()->json($classes);
    }

    public function create(Request $request)
    {
        try {
            $school = ClassModel::create([
                'name' => $request->input('name'),
                'year' => $request->input('year'),
                'shift' => $request->input('shift'),
                'school_id' => $request->input('school_id'),
                'teacher_id' => $request->input('teacher_id'),
            ]);

            Log::info('Dados da escola:', $school->toArray());

            return response()->json(['message' => 'Escola criada com sucesso'], 201);
        } catch (\Exception $e) {
            if (isset($user)) {
                $user->delete();
            }

            Log::error('Erro ao criar escola: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao criar escola'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $ClassData = ClassModel::where('id', $id)->firstOrFail();
            $ClassData->update([
                'name' => $request->input('name'),
                'year' => $request->input('year'),
                'shift' => $request->input('shift'),
                'school_id' => $request->input('school_id'),
                'teacher_id' => $request->input('teacher_id'),
            ]);

            Log::info('Dados do professor atualizados:', $ClassData->toArray());

            return response()->json(['message' => 'Professor atualizado com sucesso'], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar professor: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao atualizar professor: ' . $e->getMessage()], 500);
        }
    }


    public function show(ClassModel $classModel): JsonResponse
    {
        return response()->json($classModel);
    }

    public function delete(ClassModel $classData, $id) {
        $classData = ClassModel::where('id', $id)->firstOrFail();

        if (!$classData) {
            return response()->json(['message' => 'Professor não encontrado'], 404);
        }

        $classData->delete();

        Log::info('Turma excluída com sucesso: ' . $classData);

        return response()->json(['message' => 'Professor e usuário excluídos com sucesso'], 200);
    }

    public function listStudents(ClassModel $classModel): JsonResponse
    {
        return response()->json($classModel->students);
    }

    public function attachStudent(ClassModel $classModel, Student $student): void
    {
        $classModel->students()->attach($student);
    }

    public function detachStudent(ClassModel $classModel, Student $student): void
    {
        $classModel->students()->detach($student);
    }

    public function close(ClassModel $classModel): void
    {
        $classModel->update(['active' => false]);
    }

    public function monitoringForm(ClassModel $classModel)
    {
        $classModel->load('students');
        $pdf = Pdf::loadView('pdf.monitoring-form', ['class' => $classModel]);
        return $pdf->download('Ficha de Acompanhamento.pdf');
    }
}
