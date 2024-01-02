<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    
    public function register(Request $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = strtolower($request->email);
        $user->password = bcrypt($request->password);

        // Validations
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user->save();

        return ['message' => 'User created successfully', 'user' => $user];
    }

    public function login(Request $request)
    {
        $credentials = [
            'name' => strtolower($request->name),
            'password' => $request->password,
        ];

        // Validations
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        // Check if user exists
        $user = User::where('name', $request->name)->first();
        if (!$user) {
            return ['message' => 'User not found', 'status' => 404];
        }

        
        $token = JWTAuth::attempt($credentials);
        if (!$token) {
            return ['message' => 'Unauthorized', 'status' => 401];
        }

        return ['token' => $token, 'user' => JWTAuth::user()];
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return ['message' => 'User logged out successfully'];
    }

    public function getUser()
    {
        return JWTAuth::user();
    }
}