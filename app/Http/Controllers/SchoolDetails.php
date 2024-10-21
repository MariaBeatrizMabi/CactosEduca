<?php

namespace App\Http\Controllers;

use App\Models\ManagementSchool;
use Illuminate\Http\Request;
use App\Models\Cities;
use App\Models\Location;

class SchoolDetails extends Controller
{
    public function indexAll()
    {
        $schools = ManagementSchool::all();
        return view('schoolDetails', ['schools' => $schools]);
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


    public function index()
    {
        return view('schoolDetails');
    }

    public function indexFilteredCity($city)
    {
        return view('schoolDetails', [
            'city' => $city,
        ]);
    }

    public function indexFiltered($city, $schoolName)
    {
        return view('schoolDetails', [
            'city' => $city,
            'schoolName' => $schoolName
        ]);
    }

    public function filteredScreen()
    {
        return view('schoolDetails');
    }

    public function indexAllSchoolsByCity($city)
    {
        $schools = ManagementSchool::where('city', $city)->get();
        return view('schoolDetails', [
            'city' => $city,
            'schools' => $schools,
        ]);
    }


    public function indexAllByCity($cityName)
    {
        $city = Cities::where('id', $cityName)->first();

        if (!$city) {
            return response()->json(['error' => 'Cidade não encontrada.'], 404);
        }

        $schools = ManagementSchool::where('city_id', $city->id)->with('exams')->get();
        $locations = Location::all();

        $groupedSchools = $schools->groupBy('city_id');

        $response = $groupedSchools->map(function ($schools, $cityId) use ($locations) {
            return $schools->map(function ($school) use ($locations) {
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
        });
        return response()->json($response->values());
    }

    public function indexSchoolDetailsByCityAndSchoolView($city, $schoolName, $schoolId)
    {
        $schoolData = $this->getSchoolDetails($city, $schoolName, $schoolId);

        if (isset($schoolData['error'])) {
            return response()->json($schoolData, 404);
        }

        return view('schoolDetails', ['schoolData' => json_encode($schoolData)]);
    }

    public function getSchoolDetails($city, $schoolName, $schoolId)
    {
        $school = ManagementSchool::where('city_id', $city)
            ->where('name', $schoolName)
            ->where('id', $schoolId)
            ->with('exams')
            ->first();

        if (!$school) {
            return ['error' => 'Escola não encontrada.'];
        }

        $location = Location::find($school->location_id);
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
    }


    public function indexMultipleSchools($cityId, $schoolNames = null)
    {
        $schoolNamesArray = $schoolNames ? explode(',', $schoolNames) : [];
        $schools = ManagementSchool::where('city_id', $cityId)
        ->whereIn('name', $schoolNamesArray)
        ->get();
        
        return view('schoolDetails', [
            'city' => $cityId,
            'schools' => $schools,
        ]);
    }
}
