<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Http\Requests\City\StoreCityRequest;
use App\Services\City\StoreCityService;
use Illuminate\Http\JsonResponse;

class CitiesController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Cities::all());
    }

    public function store(
        StoreCityRequest $storeCityRequest,
        StoreCityService $storeCityService
    ): JsonResponse
    {
        $data = $storeCityRequest->validated();
        return response()->json(
            $storeCityService->run($data)
        );
    }

    public function delete(Cities $city): void
    {
        $city->delete();
    }
}
