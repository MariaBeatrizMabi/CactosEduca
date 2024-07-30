<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\Location;
use App\Models\ManagementSchool;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ManagementSchoolController extends Controller
{
    public function index()
    {
        $schools = ManagementSchool::with('user')->get();
        $cities = Cities::with('address')->get();
        $locations = Location::all();

        $groupedSchools = $schools->groupBy('city_id');

        $response = $cities->map(function ($city) use ($groupedSchools, $locations) {
            $citySchools = $groupedSchools->get($city->id, collect())->map(function ($school) use ($locations) {
                $location = $locations->firstWhere('id', $school->location_id);
                return [
                    'id' => $school->id,
                    'name' => $school->name,
                    'city_id' => $school->city_id,
                    'location' => $location ? $location->name : null,
                    'user' => [
                        'id' => $school->user->id,
                        'user_name' => $school->user->user_name,
                        'acess_cod' => $school->user->acess_cod,
                    ]
                ];
            });

            return [
                'city' => $city->name,
                'schools' => $citySchools
            ];
        })->filter(function ($cityData) {
            return $cityData['schools']->isNotEmpty();
        });

        return response()->json($response->values());
    }

    public function show($id)
    {
        $school = ManagementSchool::with('user')->find($id);

        if (!$school) {
            return response()->json(['message' => 'School not found'], 404);
        }

        $location = Location::find($school->location_id);
        $city = Cities::find($school->city_id);

        return response()->json([
            'id' => $school->id,
            'name' => $school->name,
            'city_id' => $city ?? null,
            'location' => $location ?? null,
            'acess_cod' => $school->user->acess_cod,
            'password' => $school->user->password
        ]);
    }

    public function listTeachers(ManagementSchool $managementSchool): JsonResponse
    {
        return response()->json(
            Teacher::where('school_id', $managementSchool->id)->get()
        );
    }

    public function listStudents(ManagementSchool $managementSchool): JsonResponse
    {
        return response()->json($managementSchool->students);
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
