<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PhoneVerificationController extends Controller
{
    use ApiResponse;

    /**
     * Verify the user's phone number.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'code' => ['required', 'string', 'size:6'],
            ]);

            if ($request->user()->verifyPhoneCode($request->code)) {
                return $this->successResponse(
                    message: 'Phone number verified successfully',
                    hint: 'You can now use your account'
                );
            }

            return $this->errorResponse(
                message: 'Invalid verification code',
                hint: 'The code may be incorrect or expired',
                statusCode: 422
            );
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: 'Verification failed',
                hint: 'An unexpected error occurred during verification',
                statusCode: 500
            );
        }
    }

    /**
     * Resend the verification code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if ($user->hasVerifiedPhone()) {
                return $this->errorResponse(
                    message: 'Phone already verified',
                    hint: 'This phone number has already been verified',
                    statusCode: 400
                );
            }

            $code = $user->generatePhoneVerificationCode();

            return $this->successResponse(
                data: [
                    'code' => $code, // TODO: Remove in production, only for testing
                ],
                message: 'Verification code sent successfully',
                hint: 'Please check your phone for the verification code'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: 'Failed to send verification code',
                hint: 'An unexpected error occurred',
                statusCode: 500
            );
        }
    }
}
