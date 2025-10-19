<?php

namespace App\Services\Auth;

use App\Services\BaseService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class WebAuthService extends BaseService
{
    public function verifyPhone(array $data): JsonResponse
    {
        try {
            $validationResult = $this->validateData($data, [
                'phone' => 'required|string',
                'code' => 'required|string|size:4',
            ]);

            if ($validationResult !== true) {
                return $validationResult;
            }

            $user = User::where('phone', $data['phone'])
                ->where('phone_verification_code', $data['code'])
                ->first();

            if (!$user) {
                return $this->errorResponse(
                    message: 'Invalid verification code',
                    hint: 'Please check your verification code and try again',
                    statusCode: 400
                );
            }

            // Check if code is expired
            if (now()->gt($user->phone_verification_code_expires_at)) {
                return $this->errorResponse(
                    message: 'Verification code expired',
                    hint: 'Please request a new verification code',
                    statusCode: 400
                );
            }

// Mark phone as verified
$user->markPhoneAsVerified();

// Log verification details
logger()->info('Phone verified for user: ' . $user->id);
// Mark store as verified
$user->markStoreAsVerified();

// Log store verification details
logger()->info('Store verified for user: ' . $user->id);

            return $this->successResponse(
                message: 'Phone number verified successfully',
                hint: 'You can now use your account'
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function resendVerificationCode(array $data): JsonResponse
    {
        try {
            $validationResult = $this->validateData($data, [
                'phone' => 'required|string|exists:users,phone',
            ]);

            if ($validationResult !== true) {
                return $validationResult;
            }

            $user = User::where('phone', $data['phone'])->first();

            // Check if the user has already verified their phone
            if ($user->hasVerifiedPhone()) {
                return $this->errorResponse(
                    message: 'Phone already verified',
                    hint: 'This phone number has already been verified',
                    statusCode: 400
                );
            }

// Generate a new verification code
$code = $user->generatePhoneVerificationCode();

// Log the generated verification code
logger()->info('Generated new verification code for user: ' . $user->id);

// Send SMS with the verification code
$this->sendSms($user->phone, 'Your verification code is: ' . $code);

// Log the SMS sending details
logger()->info('Sent verification code to user: ' . $user->id);

            return $this->successResponse(
                message: 'Verification code sent successfully',
                hint: 'Please check your phone for the verification code',
                data: [
                    'phone' => $user->phone,
                    // In development environment, return the code for testing
                    'code' => app()->environment('local') ? $code : null
                ]
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
