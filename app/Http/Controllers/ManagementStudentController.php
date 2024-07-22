<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ManagementStudentController extends Controller
{
    public function __invoke()
    {
        return view('student');
    }

    public function index()
    {
        $class = Student::with('classData')->get();
        return response()->json($class);
    }

    public function create(Request $request)
    {
        try {
            $student = Student::create([
                'name' => $request->input('name'),
                'group_id' => $request->input('group_id'),
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
