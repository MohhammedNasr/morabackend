<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
}
