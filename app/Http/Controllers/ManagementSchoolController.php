<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\ManagementSchool;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ManagementSchoolController extends Controller
{
    public function index()
    {
        $schools = ManagementSchool::with('user')->get();
        $cities = Cities::with('address')->get();

        $groupedSchools = $schools->groupBy('city_id');

        $response = $cities->map(function ($city) use ($groupedSchools) {
            return [
                'address' => $city->name,
                'schools' => $groupedSchools->get($city->id, collect())->map(function ($school) {
                    return [
                        'id' => $school->id,
                        'name' => $school->name,
                        'city_id' => $school->city_id,
                        'location_id' => $school->location_id,
                        'user' => [
                            'id' => $school->user->id,
                            'user_name' => $school->user->user_name,
                            'acess_cod' => $school->user->acess_cod,
                        ]
                    ];
                })
            ];
        })->filter(function ($cityData) {
            return $cityData['schools']->isNotEmpty();
        });

        return response()->json($response->values());
    }

    public function show($id)
    {
        $school = ManagementSchool::with('user')->find($id);
        $cities = Cities::with('address')->get();

        if (!$school) {
            return response()->json(['message' => 'School not found'], 404);
        }
        return response()->json([
            'id' => $school->id,
            'name' => $school->name,
            'location_id' => $school->location_id,
            'city_id' => $cities[$school->city_id - 1]->name,
            'acess_cod' => $school->user->acess_cod,
            'password' => $school->user->password 
        ]);
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
                'city_id' => $request->input('city_id'),
                'location_id' => $request->input('location_id'),
                'acess_cod' => $request->input('acess_cod'),
                'password' => $request->input('password'),
                'city' => $request->input('city'),
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
            'city_id' => $request->input('city_id'),
            'location_id' => $request->input('location_id'),
            'acess_cod' => $request->input('acess_cod'),
            'password' => $request->input('password'),
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
