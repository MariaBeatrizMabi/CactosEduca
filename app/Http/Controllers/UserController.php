<?php

namespace App\Http\Controllers;

use App\Models\ManagementSchool;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function showManagementSchools(User $user): JsonResponse
    {
        return response()->json($user->load('managementSchool'));
    }

    public function showTeacher(User $user): JsonResponse
    {
        return response()->json($user->load('teacher'));
    }

    public function school(): JsonResponse
    {
        return response()->json(ManagementSchool::first());
    }
}
