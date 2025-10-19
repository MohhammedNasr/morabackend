<?php

namespace App\Services\Supplier;

use App\Models\Supplier;
use App\Services\BaseService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForgetPasswordService extends BaseService
{
    public function sendOtp(string $phone): \Illuminate\Http\JsonResponse
    {
        $supplier = Supplier::where('phone', $phone)->first();
        if (!$supplier) {
            return $this->errorResponse('Supplier not found', 404);
        }

        $otp = rand(1000, 9999);
        $supplier->update([
            'reset_password_otp' => $otp,
            'reset_password_otp_expires_at' => now()->addMinutes(10)
        ]);

        return $this->successResponse([
            'message' => 'OTP sent successfully',
            'otp' => $otp // Remove this in production
        ]);
    }

    public function verifyOtp(string $phone, int $otp): \Illuminate\Http\JsonResponse
    {
        $supplier = Supplier::where('phone', $phone)
            ->where('reset_password_otp', $otp)
            ->where('reset_password_otp_expires_at', '>', now())
            ->first();

        if (!$supplier) {
            return $this->errorResponse('Invalid OTP or OTP expired', 400);
        }

        $token = Str::random(60);
        $supplier->update([
            'reset_password_token' => $token,
            'reset_password_token_expires_at' => now()->addMinutes(10)
        ]);

        return $this->successResponse([
            'message' => 'OTP verified successfully',
            'reset_token' => $token
        ]);
    }

    public function resetPassword(string $token, string $password): \Illuminate\Http\JsonResponse
    {
        $supplier = Supplier::where('reset_password_token', $token)
            ->where('reset_password_token_expires_at', '>', now())
            ->first();

        if (!$supplier) {
            return $this->errorResponse('Invalid or expired token', 400);
        }

        $supplier->update([
            'password' => Hash::make($password),
            'reset_password_token' => null,
            'reset_password_token_expires_at' => null,
            'reset_password_otp' => null,
            'reset_password_otp_expires_at' => null
        ]);

        return $this->successResponse(['message' => 'Password reset successfully']);
    }
}
