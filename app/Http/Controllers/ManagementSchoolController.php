<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\Location;
use App\Models\ManagementSchool;
use App\Models\Teacher;
use App\Models\User;
use App\Models\ClassModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ManagementSchoolController extends Controller
{
    public function listAvailableClassInSchools($schoolId)
    {
        $school = ManagementSchool::where('user_id', $schoolId)->first();
        $userID = $school ? $school->id : null; 
        
        $class = ClassModel::where('school_id', $school->id)->get();
        
        if (!$class) {
            return response()->json(['message' => 'Escola não encontrada'], 404);
        }

        return response()->json($class);
    }

    public function listAvailableClassForTeacherInSchools($id)
    {
        $teacherId = Teacher::where('user_id', $id)->first();
        $userID = $teacherId ? $teacherId->id : null; 
        $class = ClassModel::where('teacher_id', $userID)->get();
        
        
        if (!$class) {
            return response()->json(['message' => 'Escola não encontrada'], 404);
        }

        return response()->json($class);
    }


    public function examsAll(): JsonResponse
    {
        $schools = ManagementSchool::with('exams')->get();
        $locations = Location::all();

        $groupedSchools = $schools->groupBy('city_id');

        $response = Cities::all()->map(function ($city) use ($groupedSchools, $locations) {
            $citySchools = $groupedSchools->get($city->id, collect())->map(function ($school) use ($locations) {
                $location = $locations->firstWhere('id', $school->location_id);
                $averageGrades = $this->calculateAverageGrades($school->exams);
                
                $exams = $school->exams->map(function ($exam) {
                    return [
                        'id' => $exam->id,
                        'reading' => $exam->reading,
                        'writing' => $exam->writing,
                        'action' => $exam->action,
                    ];
                });

                return [
                    'id' => $school->id,
                    'name' => $school->name,
                    'city_id' => $school->city_id,
                    'location' => $location ? $location->name : null,
                    'acess_cod' => $school->user->acess_cod,
                    'average_grades' => $averageGrades,
                    'exams' => $exams,
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

    private function getGradeValue($grade)
    {
        $grades = [
            'pré-silábico' => 1,
            'silábico' => 2,
            'silábico-alfabético' => 3,
            'alfabético' => 4,
        ];

        return $grades[$grade] ?? 0;
    }

    public function getSchoolsByCityName($cityName)
    {
        $city = Cities::where('id', $cityName)->first();
    
        if (!$city) {
            return response()->json(['message' => 'City not found'], 404);
        }
    
        $cityId = $city->id;
    
        $schools = ManagementSchool::with('exams', 'user')
            ->where('city_id', $cityId)
            ->get();
    
        $locations = Location::all();
    
        $groupedSchools = $schools->groupBy('city_id');
    
        $response = Cities::all()->map(function ($city) use ($groupedSchools, $locations) {
            $citySchools = $groupedSchools->get($city->id, collect())->map(function ($school) use ($locations) {
                $location = $locations->firstWhere('id', $school->location_id);
                $averageGrades = $this->calculateAverageGrades($school->exams);
    
                $exams = $school->exams->map(function ($exam) {
                    return [
                        'id' => $exam->id,
                        'reading' => $exam->reading,
                        'writing' => $exam->writing,
                        'action' => $exam->action,
                    ];
                });
    
                return [
                    'id' => $school->id,
                    'name' => $school->name,
                    'city_id' => $school->city_id,
                    'location' => $location ? $location->name : null,
                    'access_code' => $school->user ? $school->user->access_code : null,
                    'average_grades' => $averageGrades,
                    'exams' => $exams,
                ];
            });
    
            return [
                'city' => $city->name,
                'schools' => $citySchools
            ];
        })->filter(function ($cityData) {
            return $cityData['schools']->isNotEmpty();
        });

        return view('schoolDetails', ['responseData' => $response->values()]);
    }
    

    public function getSchoolDetailsByCity($city_id, $school_id): JsonResponse
    {
        $school = ManagementSchool::with(['user', 'classes.students.exams'])
            ->where('city_id', $city_id)
            ->find($school_id);
    
        if (!$school) {
            return response()->json(['error' => 'Escola não encontrada.'], 404);
        }
    
        $location = Location::find($school->location_id);
        $city = Cities::find($school->city_id);
    
        $schoolData = [
            'id' => $school->id,
            'name' => $school->name,
            'city' => $city ? $city->name : 'Cidade não encontrada',
            'location' => $location ? $location->name : 'Localização não encontrada',
            'acess_cod' => $school->user ? $school->user->acess_cod : 'Não disponível',
            'password' => $school->user ? $school->user->password : 'Não disponível',
            'classes' => $school->classes->map(function ($class) {
                return [
                    'id' => $class->id,
                    'name' => $class->name,
                    'students' => $class->students->map(function ($student) {
                        return [
                            'id' => $student->id,
                            'name' => $student->name,
                            'exams' => $student->exams->map(function ($exam) {
                                return [
                                    'id' => $exam->id,
                                    'reading' => $exam->reading,
                                    'writing' => $exam->writing,
                                    'action' => $exam->action,
                                ];
                            }),
                        ];
                    }),
                ];
            }),
        ];
    
        return response()->json($schoolData);
    }

    private function calculateAverageGrades($exams)
    {
        if ($exams->isEmpty()) {
            return null;
        }

        $totalGrades = 0;
        $count = 0;

        foreach ($exams as $exam) {
            $totalGrades += $this->getGradeValue($exam->reading);
            $totalGrades += $this->getGradeValue($exam->writing);
            $totalGrades += $this->getGradeValue($exam->action);
            $count += 3;
        }

        return $count > 0 ? $totalGrades / $count : null;
    }

    public function index(Request $request)
    {
        $filters = $request->all();

        if (empty($filters)) {
            return redirect()->route('class.all');
        }

        $classes = ManagementSchool::query();

        if ($request->has('filter_name')) {
            $classes->where('name', 'like', '%' . $request->input('filter_name') . '%');
        }

        if ($request->has('filter_city')) {
            $classes->where('city_id', $request->input('filter_city'));
        }

        $classes = $classes->get();

        return view('classes.index', compact('classes'));
    }

    public function all()
    {
        $classes = ManagementSchool::all();
        return view('classes.all', compact('classes'));
    }

    public function show($id)
    {
        $school = ManagementSchool::with(['user', 'classes.students.exams'])->find($id);

        if (!$school) {
            return response()->json(['message' => 'School not found'], 404);
        }

        $location = Location::find($school->location_id);
        $city = Cities::find($school->city_id);

        $schoolData = [
            'id' => $school->id,
            'name' => $school->name,
            'city' => $city,
            'location' => $location,
            'acess_cod' => $school->user->acess_cod,
            'password' => $school->user->password,
            'classes' => $school->classes->map(function ($class) {
                return [
                    'id' => $class->id,
                    'name' => $class->name,
                    'students' => $class->students->map(function ($student) {
                        return [
                            'id' => $student->id,
                            'name' => $student->name,
                            'exams' => $student->exams->map(function ($exam) {
                                return [
                                    'id' => $exam->id,
                                    'reading' => $exam->reading,
                                    'writing' => $exam->writing,
                                    'action' => $exam->action,
                                ];
                            }),
                        ];
                    }),
                ];
            }),
        ];

        return response()->json($schoolData);
    }

    public function listTeachers(ManagementSchool $managementSchool): JsonResponse
    {
        return response()->json(
            Teacher::where('school_id', $managementSchool->id)->get()
        );
    }

    public function listAvailableStudentsClass(ManagementSchool $managementSchool): JsonResponse
    {
        $data = $managementSchool->students->whereNotIn(
            'id',
            DB::table('students')
                ->select('students.*')
                ->join('student_class', 'student_class.student_id', '=', 'students.id')
                ->join('class', 'class.id', '=', 'student_class.class_id')
                ->where('students.school_id', $managementSchool->id)
                ->where('class.active', true)
                ->get()
                ->pluck('id')
                ->toArray()
        );

        return response()->json(array_values($data->toArray()));
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
