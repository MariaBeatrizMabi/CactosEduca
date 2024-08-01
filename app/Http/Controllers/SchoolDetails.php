<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SchoolDetails extends Controller
{
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
}
