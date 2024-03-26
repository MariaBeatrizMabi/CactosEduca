<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Importe a classe Hash

class LoginController extends Controller
{
    public function Auth(Request $request)
    {
        $credentials = $request->only('acess_cod', 'password');
        
        $logins = User::all();

        $acess = $logins->map(function ($e) {
            return [
                'acess_cod' => $e->acess_cod,
                'password' => $e->password,
                'type' => $e->type, 
            ];
        });

        foreach ($acess as $login) {
            if ($credentials['acess_cod'] === $login['acess_cod'] && Hash::check($credentials['password'], $login['password'])) {
                return response()->json(['success' => true, 'type' => $login['type'], 'redirect' => '/dashboard']);
            }
        }

        return response()->json(['success' => false], 401);
    }
}