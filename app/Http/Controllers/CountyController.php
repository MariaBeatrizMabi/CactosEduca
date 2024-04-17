<?php

namespace App\Http\Controllers;

use App\Models\County;
use Illuminate\Http\Request;

class CountyController extends Controller
{
    public function index()
    {
        $county = County::all();
        return response()->json($county);
    }
}
