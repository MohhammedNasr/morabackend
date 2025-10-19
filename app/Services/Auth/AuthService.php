<?php

namespace App\Services\Auth;

use App\Services\BaseService;
use App\Services\Notification\NotificationService;
use App\Models\User;
use App\Models\Representative;
use App\Models\Supplier;
use App\Models\UserDeviceToken;
use App\Http\Resources\UserResource;
use App\Http\Resources\StoreResource;
use App\Http\Resources\RepresentativeResource;
use App\Http\Resources\SupplierResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;

class AuthService extends BaseService
{
    public function __construct(private NotificationService $notificationService) {}

    /**
     * Normalize phone number by removing country code prefix
     */
    private function normalizePhone(string $phone): string
    {
        // Remove +966 or 966 prefix if present
        $normalized = preg_replace('/^\+?966/', '', $phone);
        
        // Remove any leading zeros
        $normalized = ltrim($normalized, '0');
        
        return $normalized;
    }

    public function login(array $data): JsonResponse
    {
        try {
            // Normalize phone number
            $data['phone'] = $this->normalizePhone($data['phone']);
            
            if ($data['type'] === 'store-owner') {
                return $this->handleUserLogin($data);
            } elseif ($data['type'] === 'representative') {
                return $this->handleRepresentativeLogin($data);
            } else {
                return $this->handleSupplierLogin($data);
            }
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
    protected function handleUserLogin(array $data): JsonResponse
    {

        $user = User::where('phone', $data['phone'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return $this->errorResponse(
                message: __('api.invalid_credentials'),
                hint: __('api.invalid_credentials_hint'),
                statusCode: 401
            );
        }

        if (!$user->is_verified) {
            //  if (!$user->phone_verification_code) {
            $user->generatePhoneVerificationCode();
            //  }

            return $this->errorResponse(
                message: __('api.phone_not_verified'),
                hint: __('api.phone_not_verified_hint'),
                data: [
                    'phone' => $user->phone,
                    'requires_verification' => true,
                    'code' => app()->environment('local') ? $user->phone_verification_code : null
                ],
                statusCode: 403
            );
        }

        // check activation
        if (!$user->is_active) {
            return $this->errorResponse(
                message: __('api.account_not_activated'),
                hint: __('api.account_not_activated_hint'),
                statusCode: 405
            );
        }


        if (isset($data['token'])) {

            $this->notificationService->registerDeviceToken(
                $user,
                $data['token'],
                $data['platform'] ?? 'web',
                'user'
            );
        }

        if (isset($data['uuid'])) {
            $user->update(['uuid' => $data['uuid']]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        if ($user->role->slug === 'store-owner') {
            $user->load('stores.branches');
        } elseif ($user->role->slug === 'supplier') {
            $user->load('supplier');
        }

        $responseData = [
            'token' => $token,
            'user' => new UserResource($user),
            'is_verified' => $user->hasVerifiedPhone(),
            'store' => new StoreResource($user->stores->first())
        ];

        return $this->successResponse(
            data: $responseData,
            message: __('api.login_successful'),
            hint: __('api.login_successful_hint')
        );
    }

    protected function handleSupplierLogin(array $data): JsonResponse
    {
        $supplier = Supplier::where('phone', $data['phone'])->first();

        if (!$supplier || !Hash::check($data['password'], $supplier->password)) {
            return $this->errorResponse(
                message: __('api.invalid_credentials'),
                hint: __('api.invalid_credentials_hint'),
                statusCode: 401
            );
        }

        if (isset($data['token'])) {
            $this->notificationService->registerDeviceToken(
                $supplier,
                $data['token'],
                $data['platform'] ?? 'web',
                'supplier'
            );
        }

        //verify

        if (!$supplier->is_verified) {
            $code = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            $supplier->update(['verification_code' => $code]);
            return $this->errorResponse(
                message: __('api.phone_not_verified'),
                hint: __('api.phone_not_verified_hint'),
                data: [
                    'phone' => $supplier->phone,
                    'requires_verification' => true,
                    'code' => app()->environment('local') ? $supplier->verification_code : null
                ],
                statusCode: 403
            );
        }

        // check activation

        if (!$supplier->is_active) {
            return $this->errorResponse(
                message: __('api.account_not_activated'),
                hint: __('api.account_not_activated_hint'),
                statusCode: 405
            );
        }


        if (isset($data['uuid'])) {
            $supplier->update(['uuid' => $data['uuid']]);
        }

        $token = $supplier->createToken('auth-token')->plainTextToken;

        return $this->successResponse(
            data: [
                'token' => $token,
                'user' => new UserResource($supplier),
                'supplier' => new SupplierResource($supplier),
            ],
            message: __('api.login_successful'),
            hint: __('api.login_successful_hint')
        );
    }

    protected function handleRepresentativeLogin(array $data): JsonResponse
    {
        $representative = Representative::where('phone', $data['phone'])->first();

        if (!$representative || !Hash::check($data['password'], $representative->password)) {
            return $this->errorResponse(
                message: 'Invalid credentials',
                hint: 'Please check your phone number and password',
                statusCode: 401
            );
        }
        if (isset($data['token'])) {
            $this->notificationService->registerDeviceToken(
                $representative,
                $data['token'],
                $data['platform'] ?? 'web',
                'representative'
            );
        }

        if (isset($data['uuid'])) {
            $representative->update(['uuid' => $data['uuid']]);
        }

        $token = $representative->createToken('auth-token')->plainTextToken;

        return $this->successResponse(
            data: [
                'token' => $token,
                'user' => new UserResource($representative),
                'representative' => new RepresentativeResource($representative),
                'supplier' => new SupplierResource($representative->supplier)
            ],
            message: 'Login successful',
            hint: 'You are now logged in'
        );
    }
    public function logout($authenticatable): JsonResponse
    {
        try {
            $authenticatable->currentAccessToken()->delete();
            return $this->successResponse(
            message: __('api.logout_successful'),
            hint: __('api.logout_successful_hint')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Send OTP for password reset
     */
    public function sendForgetPasswordOtp(array $data): JsonResponse
    {
        try {
            $validationResult = $this->validateData($data, [
                'phone' => 'required|string|exists:users,phone',
            ]);

            if ($validationResult !== true) {
                return $validationResult;
            }

            $user = User::where('phone', $data['phone'])->first();

            // Generate a new verification code
            $code = $user->generatePhoneVerificationCode();
            // Send SMS with the verification code
            $this->sendSms($user->phone, 'Your verification code is: ' . $code);

            return $this->successResponse(
                message: __('api.verification_code_sent'),
                hint: __('api.verification_code_sent_hint'),
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

    /**
     * Reset password using OTP
     */
    public function resetPassword(array $data): JsonResponse
    {
        try {
            $validationResult = $this->validateData($data, [
                'phone' => 'required|string|exists:users,phone',
                'code' => 'required|string|size:4',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validationResult !== true) {
                return $validationResult;
            }

            $user = User::where('phone', $data['phone'])
                ->where('phone_verification_code', $data['code'])
                ->first();

            if (!$user) {
                return $this->errorResponse(
                    message: __('api.invalid_verification_code'),
                    hint: __('api.invalid_verification_code_hint'),
                    statusCode: 400
                );
            }

            // Check if code is expired
            if (now()->gt($user->phone_verification_code_expires_at)) {
                return $this->errorResponse(
                    message: __('api.verification_code_expired'),
                    hint: __('api.verification_code_expired_hint'),
                    statusCode: 400
                );
            }

            // Update password and clear verification code
            $user->update([
                'password' => Hash::make($data['password']),
                'phone_verification_code' => null,
                'phone_verification_code_expires_at' => null,
            ]);

            return $this->successResponse(
                message: __('api.password_reset_success'),
                hint: __('api.password_reset_success_hint')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Verify phone number with OTP
     *
     * @param array $data The data for verification
     * @param bool $isAccountVerification Whether this is for account verification (true) or password reset (false)
     */
    public function verifyPhone(array $data, bool $isAccountVerification = true): JsonResponse
    {
        try {
            $validationResult = $this->validateData($data, [
                'phone' => 'required|string',
                'code' => 'required|string|size:4',
                'uuid' => 'nullable|string',
            ]);

            // Get device token and type from headers (already handled in LoginRequest)

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

            // Update device token and uuid if provided
            if (!empty($data['device_token'])) {
                $this->notificationService->registerDeviceToken(
                    $user,
                    $data['device_token'],
                    $data['device_type'] ?? 'web',
                    'user'
                );
            }

            if (isset($data['uuid'])) {
                $user->update(['uuid' => $data['uuid']]);
            }

            // Mark phone as verified
            $user->markPhoneAsVerified();
            // Mark store as verified
            $user->markStoreAsVerified();
            // Activate the account
            $user->update(['is_active' => 1, 'status' => 'active']);

            // If this is for account verification, return user data and token
            if ($isAccountVerification) {
                // Load relationships based on role
                if ($user->role->slug === 'store-owner') {
                    $user->load('stores.branches');
                    $storeVerification = new StoreResource($user->stores->first());
                } elseif ($user->role->slug === 'supplier') {
                    $user->load('supplier');
                }

                // Generate token for auto-login after verification
                $token = $user->createToken('auth-token')->plainTextToken;

                $responseData = [
                    'token' => $token,
                    'user' => new UserResource($user),
                    'store' => $storeVerification,
                    'is_verified' => true
                ];

                // Add store verification info for store owners
                // if ($user->role->slug === 'store-owner') {
                //     $responseData['store_verification'] = $storeVerification;
                // }

                return $this->successResponse(
                    data: $responseData,
                message: __('api.phone_verified_success'),
                hint: __('api.phone_verified_success_hint')
                );
            }

            // For password reset verification, just return success
            return $this->successResponse(
                message: __('api.phone_verified_success'),
                hint: __('api.phone_verified_request_hint')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Resend verification code
     */
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
                message: __('api.phone_already_verified'),
                hint: __('api.phone_already_verified_hint'),
                    statusCode: 400
                );
            }

            // Generate a new verification code
            $code = $user->generatePhoneVerificationCode();

            // Send SMS with the verification code
            $this->sendSms($user->phone, 'Your verification code is: ' . $code);

            return $this->successResponse(
                message: __('api.verification_code_sent'),
                hint: __('api.verification_code_sent_hint'),
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

    /**
     * Delete user account (soft delete)
     */
    public function deleteAccount(User $user): JsonResponse
    {
        try {
            // Revoke all tokens
            $user->tokens()->delete();

            // Soft delete the user
            $user->delete();

            return $this->successResponse(
                message: __('api.account_deleted_success'),
                hint: __('api.account_deleted_success_hint')
            );
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
