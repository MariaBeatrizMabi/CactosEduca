<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $location = Location::create([
            'name' => $request->input('name'),
        ]);

        return response()->json($location);
    }
}
