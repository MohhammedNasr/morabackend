<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\AuthService;

class LoginController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
     
        return $this->authService->login($data);
    }

    public function logout()
    {
        // Try all possible guards
        foreach (['api', 'supplier', 'representative'] as $guard) {
            if ($authenticatable = auth($guard)->user()) {
                return $this->authService->logout($authenticatable);
            }
        }

        return response()->json(['message' => 'No authenticated user found'], 401);
    }
}
