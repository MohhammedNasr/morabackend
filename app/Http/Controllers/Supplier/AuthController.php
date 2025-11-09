<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Supplier login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $supplier = Supplier::where('email', $request->email)->first();

        if (!$supplier || !Hash::check($request->password, $supplier->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        if (!$supplier->is_active) {
            return response()->json([
                'message' => 'Your account has been deactivated. Please contact support.'
            ], 403);
        }

        // Create token
        $token = $supplier->createToken('supplier-portal')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'supplier' => [
                'id' => $supplier->id,
                'business_name' => $supplier->business_name ?? $supplier->name,
                'email' => $supplier->email,
                'contact_name' => $supplier->contact_name,
                'is_verified' => $supplier->is_verified,
            ],
            'token' => $token,
        ]);
    }

    /**
     * Supplier registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_name' => 'required|string|max:255',
            'contact_name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'required|string',
            'commercial_registration' => 'nullable|string',
            'tax_id' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $supplier = Supplier::create([
            'business_name' => $request->business_name,
            'name' => $request->business_name, // Legacy field
            'contact_name' => $request->contact_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'commercial_record' => $request->commercial_registration, // Database uses commercial_record
            'tax_id' => $request->tax_id,
            'is_active' => false, // Requires admin approval
            'is_verified' => false,
        ]);

        return response()->json([
            'message' => 'Registration successful. Your account is pending approval.',
            'supplier' => [
                'id' => $supplier->id,
                'business_name' => $supplier->business_name,
                'email' => $supplier->email,
            ],
        ], 201);
    }

    /**
     * Get authenticated supplier info
     */
    public function me(Request $request)
    {
        $supplier = $request->user();

        return response()->json([
            'supplier' => [
                'id' => $supplier->id,
                'business_name' => $supplier->business_name ?? $supplier->name,
                'contact_name' => $supplier->contact_name,
                'email' => $supplier->email,
                'phone' => $supplier->phone,
                'address' => $supplier->address,
                'city' => $supplier->city,
                'country' => $supplier->country,
                'commercial_registration' => $supplier->commercial_registration,
                'tax_id' => $supplier->tax_id,
                'bank_name' => $supplier->bank_name,
                'account_number' => $supplier->account_number,
                'iban' => $supplier->iban,
                'beneficiary_name' => $supplier->beneficiary_name,
                'settlement_frequency' => $supplier->settlement_frequency,
                'is_active' => $supplier->is_active,
                'is_verified' => $supplier->is_verified,
            ],
        ]);
    }

    /**
     * Supplier logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
