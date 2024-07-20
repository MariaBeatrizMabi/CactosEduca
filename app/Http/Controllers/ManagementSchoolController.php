<?php

namespace App\Http\Controllers;

use App\Models\ManagementSchool;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ManagementSchoolController extends Controller
{
    public function index()
    {
        $schools = ManagementSchool::with('user')->get();
        return response()->json($schools);
    }

    public function show(ManagementSchool $managementSchool)
    {
        return response()->json(
            $managementSchool->load('user')
        );
    }

    public function create(Request $request)
    {
        try {
            $user = User::create([
                'user_name' => $request->input('name'),
                'acess_cod' => $request->input('acess_cod'),
                'type' => 'school',
                'password' => $request->input('password'),
            ]);

            Log::info('Dados do usuário:', $user->toArray());

            $school = ManagementSchool::create([
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'city' => $request->input('city'),
                'zip_code' => $request->input('zip_code'),
                'user_id' => $user->id,
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

    public function update(Request $request, ManagementSchool $managementSchool)
    {
        $managementSchool->update([
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'zip_code' => $request->input('zip_code'),
            'type' => $request->input('type'),
        ]);

        $managementSchool->user->update(array_filter([
            'user_name' => $request->input('name'),
            'acess_cod' => $request->input('acess_cod'),
            'password' => $request->input('password'),
        ]));

        return response()->json(['message' => 'Escola atualizada com sucesso'], 200);
    }

    public function delete(ManagementSchool $managementSchool)
    {
        $managementSchool->delete();
        return response()->json(['message' => 'Escola e usuário excluídos com sucesso'], 200);
    }
}
