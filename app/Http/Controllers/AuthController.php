<?php

namespace App\Http\Controllers;

use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json(['message' => 'invalid credentials'], 401);
            }
            $user=User::where('email','=',$request->email)->firstOrFail(); 
            // create token "bearer"
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
            'message' => 'success', 
            'access_token' => $token, 
            'token_type' => 'Bearer',
            'user' => new UserResource($user)
            ]
            , 200);
  
        } catch (Exception $e) {
            return response()->json(['message' => 'something went wrong', 'error' => $e->getMessage()], 500);
        }
    }
    public function register(StoreUserRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);
            return response()->json([
                'message' => 'user created successfully',
                'user' => $user
            ], 201);
        } catch (Exception $e) {
            return response()->json(['message' => 'something went wrong', 'error' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request) {
        // remove token from personalAccessToken table 
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'success'], 200);
    }
}
