<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VerificationController extends Controller
{
    use ApiResponse;

    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function verify(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'phone' => 'required|string',
                'code' => 'required|string|size:4',
                'is_account_verification' => 'sometimes|boolean',
            ]);

            $isAccountVerification = $request->input('is_account_verification', true);

            return $this->authService->verifyPhone($request->all(), $isAccountVerification);
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

    public function user(Request $request): JsonResponse
    {
        try {
            $user = $request->user()->load(['store.branches']);

            return $this->successResponse(
                data: ['user' => new UserResource($user)],
                message: 'User details retrieved successfully'
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: 'Failed to retrieve user details',
                hint: 'An unexpected error occurred',
                statusCode: 500
            );
        }
    }

    /**
     * Resend verification code
     */
    public function resend(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'phone' => 'required|string|exists:users,phone',
            ]);

            return $this->authService->resendVerificationCode($request->all());
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: 'Failed to resend verification code',
                hint: 'An unexpected error occurred',
                statusCode: 500
            );
        }
    }

    /**
     * Delete user account (soft delete)
     */
    public function deleteAccount(Request $request): JsonResponse
    {
        try {
            return $this->authService->deleteAccount($request->user());
        } catch (\Exception $e) {
            return $this->errorResponse(
                message: 'Failed to delete account',
                hint: 'An unexpected error occurred',
                statusCode: 500
            );
        }
    }
}