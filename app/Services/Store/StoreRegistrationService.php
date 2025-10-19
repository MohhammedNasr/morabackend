<?php

namespace App\Services\Store;

use App\Services\BaseService;
use App\Models\Role;
use App\Models\Store;
use App\Models\StoreBranch;
use App\Models\User;
use App\Http\Resources\StoreResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class StoreRegistrationService extends BaseService
{
    /**
     * Register a new store with owner and branches
     */
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

    public function register(array $data): JsonResponse
    {
        try {
            // Normalize phone number
            $data['phone'] = $this->normalizePhone($data['phone']);
            
            // log sanitized request (avoid sensitive fields)
            $safe = $data;
            unset($safe['password'], $safe['password_confirmation']);
            Log::info('Store registration request', $safe);
            return DB::transaction(function () use ($data) {
                // Create user
                $role = Role::where('slug', 'store-owner')->firstOrFail();

                $userData = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'phone' => $data['phone'],
                    'role_id' => $role->id,
                    'is_active' => 0,
                    'status' => "inactive"
                ];

                // Add device_token and uuid if provided
                if (isset($data['device_token'])) {
                    $userData['device_token'] = $data['device_token'];
                }

                if (isset($data['uuid'])) {
                    $userData['uuid'] = $data['uuid'];
                }

                $user = User::create($userData);

                // Generate verification code using the trait method
                $code = $user->generatePhoneVerificationCode();

                // Create store
                $store = Store::create([
                    'owner_id' => $user->id,
                    'name' => $data['stores']['name'],
                    'type' => $data['stores']['type'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'commercial_record' => $data['stores']['commercial_record'],
                    'tax_record' => $data['stores']['tax_record'],
                    'tax_number' => $data['stores']['tax_record'],
                    'id_number' => $data['stores']['id_number'],
                    'is_verified' => false,
                    'credit_balance' => $data['stores']['credit_balance'] ?? 0,
                ]);

                // Create store user relationship
                $store->users()->attach($user->id, ['is_primary' => true]);

                // Create branches
                foreach ($data['branch'] as $index => $branchData) {
                    StoreBranch::create([
                        'store_id' => $store->id,
                        'name' => $branchData['name'],
                        'phone' => $this->normalizePhone($branchData['phone']),
                        // Flutter sends 'address'; fall back to 'street_name' if provided
                        'street_name' => $branchData['address'] ?? ($branchData['street_name'] ?? ''),
                        'latitude' => $branchData['latitude'],
                        'longitude' => $branchData['longitude'],
                        'city_id' => $branchData['city_id'],
                        'area_id' => $branchData['area_id'] ?? null,
                        'building_number' => $branchData['building_number'] ?? null,
                        'floor_number' => $branchData['floor_number'] ?? null,
                        'is_main' => $index === 0, // First branch is main

                    ]);
                }

                // TODO: Send verification code via SMS

                return $this->successResponse(
                    data: [
                        'user' => new UserResource($user),
                        'store' => new StoreResource($store->load('branches')),
                        'code' => $code
                    ],
                    message: 'Store registered successfully. Please verify your phone number.',
                    statusCode: 201
                );
            });
        } catch (\Exception $e) {
            Log::error('Store registration failed: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->handleException($e);
        }
    }
}
