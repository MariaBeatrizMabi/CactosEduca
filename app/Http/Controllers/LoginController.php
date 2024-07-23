<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function Auth(Request $request)
    {
        Auth::logout();
        $credentials = $request->only('acess_cod', 'password');

        $logins = User::all();

        foreach ($logins as $login) {
            if ($credentials['acess_cod'] === $login->acess_cod && Hash::check($credentials['password'], $login->password)) {
                if (Auth::attempt($credentials)) {
                    return response()->json([
                        'success' => true,
                        'type' => $login->type,
                        'redirect' => '/dashboard',
                        'token' => $login->createToken('Access Token')->plainTextToken
                    ]);
                }
            }
        }

        return response()->json(['success' => false], 401);
    }

    public function GetAuth(Request $request) {
        if (Auth::check()) {
            $user = Auth::user();
            return response()->json(['success' => true, 'id' => $user->id, 'name' => $user->user_name, 'type' => $user->type]);
        }

        return response()->json(['success' => false], 401);
    }
}
