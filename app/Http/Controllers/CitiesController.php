<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    public function index()
    {
        $cities = cities::all();
        return response()->json($cities);
    }
}
