<?php

namespace App\Http\Controllers;

use App\Models\managementSchooll;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ManagementSchoollController extends Controller
{
    public function index()
    {
        $escolas = managementSchooll::all();
        return response()->json($escolas);
    }

    public function create(Request $request)
    {
        try {
            $user = User::create([
                'id' => $request->input('id'), 
                'user_name' => $request->input('name'), 
                'acess_cod' => $request->input('acess_cod'),
                'type' => 'school', 
                'password' => $request->input('password'),
            ]);
        
            Log::info('Dados do usuário:', $user->toArray());
        
            $school = ManagementSchooll::create([
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'city' => $request->input('city'),
                'acess_cod' => $request->input('acess_cod'),
                'zip_code' => $request->input('zip_code'),
                'type' => $request->input('type'),
                'password' => $request->input('password'),
                'school_id' => $user->id,
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

     public function update(Request $request, ManagementSchooll $managementSchooll, User $user)
    {
        $managementSchooll->update([
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'acess_cod' => $request->input('acess_cod'),
            'zip_code' => $request->input('zip_code'),
            'type' => $request->input('type'),
            'password' => $request->input('password'),
        ]);

        $user = User::find($managementSchooll->user_id);

        if ($user) {
            $user->update([
                'user_name' => $request->input('name'), 
                'acess_cod' => $request->input('acess_cod'),
                'password' => $request->input('password'),
            ]);
        }

        return response()->json(['message' => 'Escola atualizada com sucesso'], 200);
    }

    public function delete(ManagementSchooll $managementSchooll) {
        if (!$managementSchooll) {
            return response()->json(['message' => 'Escola não encontrada'], 404);
        }
        
        $user = $managementSchooll->user;
    
        $managementSchooll->delete();
        
        if ($user) {
            $user->delete();
        }
        
        return response()->json(['message' => 'Escola e usuário excluídos com sucesso'], 200);
    }
   
    public function show(ManagementSchooll $managementSchooll)
    {
        return response()->json($managementSchooll);
    }
}