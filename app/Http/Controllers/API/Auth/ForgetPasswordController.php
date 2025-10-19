<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgetPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ForgetPasswordController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Send OTP to user's phone for password reset
     */
    public function sendOtp(ForgetPasswordRequest $request): JsonResponse
    {
        return $this->authService->sendForgetPasswordOtp($request->all());
    }

    /**
     * Verify OTP for password reset
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => 'required|string|exists:users,phone',
            'code' => 'required|string|size:4',
        ]);

        // Pass false to indicate this is not for account verification
        return $this->authService->verifyPhone($request->all(), false);
    }

    /**
     * Reset password using OTP
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        return $this->authService->resetPassword($request->all());
    }
}
