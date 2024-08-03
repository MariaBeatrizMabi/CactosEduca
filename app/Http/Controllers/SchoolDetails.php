<?php

namespace App\Http\Controllers;

use App\Models\ManagementSchool;
use Illuminate\Http\Request;

class SchoolDetails extends Controller
{
    public function indexAll()
    {
        $schools = ManagementSchool::all();
        return view('schoolDetailsAll', compact('schools'));
    }

    public function index() {
        return view('schoolDetails');
    }

    public function indexFilteredCity($city) {
        return view('schoolDetails', [
            'city' => $city,
        ]);
    }

    public function indexFiltered($city, $schoolName) {
        return view('schoolDetails', [
            'city' => $city,
            'schoolName' => $schoolName
        ]);
    }

    public function filteredScreen() {
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

    public function indexAllByCity($city)
    {
        $cityData = ManagementSchool::where('city', $city)->get();
        return view('schoolDetails', ['city' => $city, 'schools' => $cityData]);
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
