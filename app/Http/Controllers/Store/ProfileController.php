<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function edit(Request $request)
    {
        return view('store.profile.edit', [
            'user' => $request->user(),
            'store' => $request->user()->store,
        ]);
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
        $store = $user->store;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'store_name' => ['required', 'string', 'max:255'],
            'tax_record' => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($user, $store, $validated) {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            $store->update([
                'name' => $validated['store_name'],
                'tax_record' => $validated['tax_record'],
            ]);
        });

        return back()->with('success', __('Profile updated successfully.'));
    }
}
