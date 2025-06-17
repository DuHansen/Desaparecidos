<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $result = User::login($request->email, $request->password);

        if (!$result['success']) {
            return response()->json(['error' => $result['message']], 401);
        }

        return response()->json(['message' => 'Login realizado com sucesso', 'user' => $result['user']]);
    }
}
