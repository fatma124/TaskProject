<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Libraries\ApiResponse;
use App\Libraries\ApiValidator;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;



class AuthController extends Controller
{
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

            return response()->json($user, 201);
        }

        public function login(Request $request)
        {
            $credentials = $request->only('email', 'password');

            if (!$token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Email Or Password is Wrong'], 401);
            }

            return response()->json([
                'token' => $token
            ]);
        }

 
  }
