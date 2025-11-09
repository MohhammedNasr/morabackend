<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function edit(Request $request)
    {
        $supplier = $request->user();

        if ($supplier->role->slug !== 'supplier') {
            abort(403, 'Unauthorized');
        }

        return view('supplier.profile.edit', compact('supplier'));
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = $request->user();
        $supplier = $user->supplier;

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'required_with:current_password', 'confirmed', Password::defaults()],
            'commercial_record' => ['required', 'string', 'unique:suppliers,commercial_record,' . $supplier->id],
            'payment_term_days' => ['required', 'integer', 'min:1', 'max:365'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        $supplier->update([
            'name' => $request->name,
            'commercial_record' => $request->commercial_record,
            'payment_term_days' => $request->payment_term_days,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update supplier profile (API)
     */
    public function updateProfile(Request $request)
    {
        $supplier = $request->user();

        $validator = Validator::make($request->all(), [
            'business_name' => 'sometimes|string|max:255',
            'contact_name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string',
            'address' => 'sometimes|string',
            'city' => 'sometimes|string',
            'country' => 'sometimes|string',
            'bank_name' => 'sometimes|string',
            'account_number' => 'sometimes|string',
            'iban' => 'sometimes|string',
            'beneficiary_name' => 'sometimes|string',
            'settlement_frequency' => 'sometimes|in:weekly,bi-weekly,monthly',
            'notify_on_transaction' => 'sometimes|boolean',
            'notify_on_settlement' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $supplier->update($request->only([
                'business_name',
                'contact_name',
                'phone',
                'address',
                'city',
                'country',
                'bank_name',
                'account_number',
                'iban',
                'beneficiary_name',
                'settlement_frequency',
                'notify_on_transaction',
                'notify_on_settlement',
            ]));

            return response()->json([
                'message' => 'Profile updated successfully',
                'supplier' => [
                    'id' => $supplier->id,
                    'business_name' => $supplier->business_name,
                    'contact_name' => $supplier->contact_name,
                    'email' => $supplier->email,
                    'phone' => $supplier->phone,
                    'address' => $supplier->address,
                    'city' => $supplier->city,
                    'country' => $supplier->country,
                    'bank_name' => $supplier->bank_name,
                    'account_number' => $supplier->account_number,
                    'iban' => $supplier->iban,
                    'beneficiary_name' => $supplier->beneficiary_name,
                    'settlement_frequency' => $supplier->settlement_frequency,
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Supplier profile update error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to update profile',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Change password (API)
     */
    public function changePassword(Request $request)
    {
        $supplier = $request->user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!Hash::check($request->current_password, $supplier->password)) {
            return response()->json([
                'error' => 'Current password is incorrect'
            ], 422);
        }

        try {
            $supplier->update([
                'password' => Hash::make($request->new_password),
            ]);

            return response()->json([
                'message' => 'Password changed successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Supplier password change error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to change password',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
