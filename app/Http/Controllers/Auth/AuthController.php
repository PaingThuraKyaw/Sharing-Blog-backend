<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $valiated = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);

        return response()->json([
            "data" => "Register success"
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => "required|email",
            'password' => "required",
        ]);


        if (Auth::attempt($credentials)) {
            $token = $request->user()->createToken('tokenName');
            return response()->json([
                "success" => true,
                "token" => $token->plainTextToken,

            ]);
        }

        return response()->json([
            'error' => 'Invalid credentials',
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => 'Logout successful',
        ]);
    }
}
