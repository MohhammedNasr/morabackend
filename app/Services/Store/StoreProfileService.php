<?php

namespace App\Services\Store;

use App\Http\Resources\StoreResource;
use App\Http\Resources\UserResource;
use App\Services\BaseService;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StoreProfileService extends BaseService
{
    public function updateProfile(User $user, array $data)
    {

        // dd($user);
        try {
            return DB::transaction(function () use ($data, $user) {
                $store = Store::where('owner_id', $user->id)->first();

                // Update store profile
                $store->update([
                    'name' => $data['name'],
                ]);

                // Update user email if changed
                if (isset($data['email'])) {
                    $user->email = $data['email'];
                }

                // Update password if provided
                if (!empty($data['password'])) {
                    $user->password = Hash::make($data['password']);
                }

                $user->save();
                return $this->successResponse(
                    data: [
                        'user' => new UserResource($user),
                        'store' => new StoreResource($store->load('branches'))
                    ],
                    message: 'Store registered successfully. Please verify your phone number.',
                    statusCode: 201
                );
            });
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
