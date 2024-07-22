<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showManagementSchools(User $user)
    {
        return response()->json($user->load('managementSchool'));
    }
}
