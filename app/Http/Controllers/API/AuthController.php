<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
            ], 200);
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray();
            $firstError = reset($errors)[0];
            return response()->json(['error' => $firstError], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('authToken')->plainTextToken;
        return response()->json([
            'token' => $token,
        ], 201);
    }

    public function profile()
    {
        return response()->json(Auth()->user(), 200);
    }

    public function logout()
    {
        // $request->user()->token()->revoke();

        // return response()->json(['message' => 'Successfully logged out'], 200);
        $user = Auth::guard('sanctum')->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        try {
            $user->tokens()->delete();
        } catch (\Exception $e) {
            Log::error('Token revocation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to revoke tokens'], 500);
        }
        return response()->json(['message' => 'Successfully logged out'], 200);
    }
}
