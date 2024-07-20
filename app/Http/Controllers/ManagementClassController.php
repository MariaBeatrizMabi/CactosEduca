<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\managementSchool;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ManagementClassController extends Controller
{
    public function index()
    {
        $class = ClassModel::with('teacher')->get();
        return response()->json($class);
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

    public function update(Request $request, ClassModel $school)
    {
        try {
            $school->update([
                'name' => $request->input('name'),
                'school_id' => $request->input('school_id'),
                'teacher_id' => $request->input('teacher_id'),
            ]);

            Log::info('Dados da escola atualizados:', $school->toArray());

            return response()->json(['message' => 'Escola atualizada com sucesso'], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar escola: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao atualizar escola'], 500);
        }
    }

//     public function delete(ManagementSchooll $managementSchooll) {
//         if (!$managementSchooll) {
//             return response()->json(['message' => 'Escola não encontrada'], 404);
//         }

//         $user = $managementSchooll->user;

//         $managementSchooll->delete();

//         if ($user) {
//             $user->delete();
//         }

//         return response()->json(['message' => 'Escola e usuário excluídos com sucesso'], 200);
//     }

    public function show(ClassModel $classModel)
    {
        return response()->json($classModel);
    }
}
