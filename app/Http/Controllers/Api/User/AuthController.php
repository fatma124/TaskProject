<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
       $data = $request->validate(
            [
                'name'     => 'required|string',
                'email'    => 'required|email|unique:users,email',
                'phone'    => 'required|unique:users,phone',
                'password' => 'required|min:6'
            ],
            [
                'name.required'     => 'Name is required',
                'email.required'    => 'Email is required',
                'phone.required'    => 'Phone is required',
                'phone.unique'    => 'Phone already exists',
                'email.unique'    => 'Email already exists',
                'password.required' => 'Password is required',
                'password.min'      => 'Password must be at least 6 characters'
            ]
            );

        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'status' => 'success',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    // Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'token' => $token,
            'user' => auth()->user()
        ]);
    }

    // Logout
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['status' => 'success', 'message' => 'Logged out']);
    }
}
