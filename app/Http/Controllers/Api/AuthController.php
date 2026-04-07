<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::query()->create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $token = $user->createToken('auth_token')->accessToken;

        return new \Illuminate\Http\JsonResponse([
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function login(Request $request): JsonResponse
    {
        if (! Auth::attempt($request->only(['email', 'password']))) {
            return new \Illuminate\Http\JsonResponse([
                'message' => 'Invalid login details',
            ], 401);
        }

        $user = User::query()->where('email', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->accessToken;

        return new \Illuminate\Http\JsonResponse([
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
