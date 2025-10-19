<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use App\Models\Wallet;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    use ApiResponse;

    public function registerStore(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => ['required', 'confirmed', Password::defaults()],
                'phone' => 'required|string|unique:stores',
                'store_name' => 'required|string|max:255',
                'tax_record' => 'required|string|unique:stores',
                'commercial_record' => 'required|string|unique:stores',
            ]);

            DB::beginTransaction();

            $storeOwnerRole = Role::where('slug', 'store-owner')->firstOrFail();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $storeOwnerRole->id,
            ]);

            $store = Store::create([
                'owner_id' => $user->id,
                'name' => $request->store_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'tax_record' => $request->tax_record,
                'commercial_record' => $request->commercial_record,
                'verification_code' => Str::random(6),
                'verification_code_expires_at' => now()->addMinutes(10),
            ]);

            Wallet::create([
                'store_id' => $store->id,
                'balance' => 0,
            ]);

            DB::commit();

            // TODO: Send verification code via SMS

            return $this->successResponse(
                data: [
                    'store_id' => $store->id,
                    'verification_code' => $store->verification_code, // TODO: Remove this in production
                ],
                message: 'Store registration successful',
                hint: 'Please verify your store using the code sent to your phone',
                statusCode: 201
            );
        } catch (ValidationException $e) {
            DB::rollBack();
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(
                message: 'Registration failed',
                hint: 'An unexpected error occurred during registration',
                statusCode: 500
            );
        }
    }

    public function registerSupplier(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => ['required', 'confirmed', Password::defaults()],
                'commercial_record' => 'required|string|unique:suppliers',
                'payment_term_days' => 'required|integer|min:0',
            ]);

            DB::beginTransaction();

            $supplierRole = Role::where('slug', 'supplier')->firstOrFail();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $supplierRole->id,
            ]);

            $supplier = $user->supplier()->create([
                'name' => $request->name,
                'commercial_record' => $request->commercial_record,
                'payment_term_days' => $request->payment_term_days,
            ]);

            DB::commit();

            return $this->successResponse(
                data: ['supplier_id' => $supplier->id],
                message: 'Supplier registration successful',
                hint: 'You can now log in with your credentials',
                statusCode: 201
            );
        } catch (ValidationException $e) {
            DB::rollBack();
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(
                message: 'Registration failed',
                hint: 'An unexpected error occurred during registration',
                statusCode: 500
            );
        }
    }

    public function registerRepresentative(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => ['required', 'confirmed', Password::defaults()],
                'phone' => 'required|string|unique:users,phone',
            ]);

            DB::beginTransaction();

            $representativeRole = Role::where('slug', 'representative')->firstOrFail();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role_id' => $representativeRole->id,
            ]);

            DB::commit();

            return $this->successResponse(
                data: ['user_id' => $user->id],
                message: 'Representative registration successful',
                hint: 'You can now log in with your credentials',
                statusCode: 201
            );
        } catch (ValidationException $e) {
            DB::rollBack();
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse(
                message: 'Registration failed',
                hint: 'An unexpected error occurred during registration',
                statusCode: 500
            );
        }
    }
}
