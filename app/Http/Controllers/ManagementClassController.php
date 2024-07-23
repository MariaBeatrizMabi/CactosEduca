<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ManagementClassController extends Controller
{
    public function index()
    {
        return response()->json(
            ClassModel::with('teacher')->get()
        );
    }

    public function create(Request $request)
    {
        try {
            $school = ClassModel::create([
                'name' => $request->input('name'),
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

    public function listStudents(ClassModel $classModel)
    {
        return response()->json($classModel->students);
    }
}
