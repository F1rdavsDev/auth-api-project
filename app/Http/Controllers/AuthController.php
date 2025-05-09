<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

       $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'name' => $user->name,
        'access_token' => $token,
        'token_type' => 'Bearer',
    ]);
    }

    public function login(LoginRequest $request)
    {
        
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Login error'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'name' => $user->name,
        'access_token' => $token,
        'token_type' => 'Bearer',
    ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    }
}
