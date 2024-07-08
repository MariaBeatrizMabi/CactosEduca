<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TeacherController extends Controller
{
    public function index()
    {
        $escolas = Teacher::all();
        return response()->json($escolas);
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
                'acess_cod' => $request->input('acess_cod'),
                'type' => $request->input('type'),
                'password' => $request->input('password'),
                'school_id' => $request->input('school_id'),
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

    public function update(Request $request, $id)
    {
        try {
            $user = User::where('id', $id)->firstOrFail();
            
            $existingUser = User::where('acess_cod', $request->input('acess_cod'))->where('id', '!=', $id)->first();
            if ($existingUser) {
                Log::info('Tentativa de atualização do código de acesso já existente: ', [
                    'user_id' => $id,
                    'attempted_acess_cod' => $request->input('acess_cod'),
                    'existing_user_id' => $existingUser->id
                ]);
    
                $acess_cod = $user->acess_cod;
            } else {
                $acess_cod = $request->input('acess_cod');
            }
    
            $user->update([
                'user_name' => $request->input('name'), 
                'acess_cod' => $acess_cod,
                'type' => 'teacher', 
                'password' => bcrypt($request->input('password')), 
            ]);
    
            Log::info('Dados do usuário atualizados:', $user->toArray());
    
            $school = Teacher::where('id', $id)->firstOrFail();
            $school->update([
                'name' => $request->input('name'),
                'acess_cod' => $acess_cod,
                'type' => $request->input('type'),
                'password' => bcrypt($request->input('password')), 
            ]);
    
            Log::info('Dados do professor atualizados:', $school->toArray());
    
            return response()->json(['message' => 'Professor atualizado com sucesso'], 200);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar professor: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao atualizar professor: ' . $e->getMessage()], 500);
        }
    }
    
    public function delete(Request $request, $id) {
        $teacher = Teacher::find($id);
    
        if (!$teacher) {
            return response()->json(['message' => 'Professor não encontrado'], 404);
        }
    
        $user = User::where('id', $teacher->id)->first();
    
        DB::beginTransaction();
    
        try {
            $teacher->delete();
    
            if ($user) {
                $user->delete();
            }
    
            DB::commit();
    
            return response()->json(['message' => 'Professor e usuário excluídos com sucesso'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Erro ao excluir professor e usuário', 'error' => $e->getMessage()], 500);
        }
    }
   
}