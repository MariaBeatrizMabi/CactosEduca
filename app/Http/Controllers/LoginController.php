<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Importe a classe Hash
use Illuminate\Support\Facades\Auth;

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
                    return response()->json(['success' => true, 'type' => $login->type, 'redirect' => '/dashboard']);
                }
            }
        }

        return response()->json(['success' => false], 401);
    }
}