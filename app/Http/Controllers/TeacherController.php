<?php

namespace App\Http\Controllers;

use App\Models\ManagementSchool;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeacherController extends Controller
{
    public function all()
    {
        return Teacher::all();
    }
    
    public function index()
    {
        $user = Auth::user();
        $school = ManagementSchool::where('user_id', $user->id)->get();
        
        $schoolIds = $school->pluck('id');
        if ($schoolIds->isNotEmpty()) {
            $teachers = Teacher::with('user')
                ->whereIn('school_id', $schoolIds)
                ->get();
        } else {
            $teachers = collect(); // Empty collection
        }

        return response()->json($teachers);
    }

    public function show(Teacher $teacher)
    {
        return response()->json($teacher->load('user'));
    }

    public function create(Request $request)
    {
        try {
            $user = User::create([
                'user_name' => $request->input('name'),
                'acess_cod' => $request->input('acess_cod'),
                'type' => 'teacher',
                'password' => bcrypt($request->input('password')),
            ]);

            Log::info('Dados do usuário:', $user->toArray());

            $school = Teacher::create([
                'name' => $request->input('name'),
                'school_id' => $request->input('school_id'),
                'user_id' => $user->id
            ]);

            Log::info('Dados do professor:', $school->toArray());

            return response()->json(['message' => 'Professor criado com sucesso'], 201);
        } catch (\Exception $e) {
            if (isset($user)) {
                $user->delete();
            }

            Log::error('Erro ao criar professor: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao criar professor' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Teacher $teacher)
    {
        try {
            $existingUser = User::where('acess_cod', $request->input('acess_cod'))
                ->whereNot('id', $teacher->user_id)
                ->first();

            if ($existingUser) {
                Log::info('Tentativa de atualização do código de acesso já existente: ', [
                    'user_id' => $teacher->user_id,
                    'attempted_acess_cod' => $request->input('acess_cod'),
                    'existing_user_id' => $existingUser->id
                ]);

                $acess_cod = $teacher->user->acess_cod;
            } else {
                $acess_cod = $request->input('acess_cod');
            }

            $teacher->user->update([
                'user_name' => $request->input('name'),
                'acess_cod' => $acess_cod,
                'type' => 'teacher',
                'password' => bcrypt($request->input('password')),
            ]);

            Log::info('Dados do usuário atualizados:', $teacher->user->toArray());

            $teacher->update([
                'name' => $request->input('name')
            ]);

            Log::info('Dados do professor atualizados:', $teacher->toArray());

            return response()->json(['message' => 'Professor atualizado com sucesso'], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar professor: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao atualizar professor: ' . $e->getMessage()], 500);
        }
    }

    public function delete(Teacher $teacher) {
        if (!$teacher) {
            return response()->json(['message' => 'Professor não encontrado'], 404);
        }

        $user = User::where('acess_cod', $teacher->acess_cod)->first();

        $teacher->delete();

        if ($user) {
            $user->delete();
        }

        Log::info('Professor e usuário excluídos com sucesso: ' . $teacher);

        return response()->json(['message' => 'Professor e usuário excluídos com sucesso'], 200);
    }
}
