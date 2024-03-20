<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function login(LoginRequest $request) : JsonResponse
    {   
        $request->authenticate();

        $user = $request->user();

        $user->tokens()->delete();

        $token = $user->createToken('api-token');

        return response()->json([
            'user' => $user,
            'token' => $token->plainTextToken,
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged Out'
        ];
    }
}
