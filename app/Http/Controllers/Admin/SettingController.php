<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => ['required', 'array'],
            'settings.name' => ['required', 'string', 'max:255'],
            'settings.logo' => ['required', 'string', 'max:255'],
            'settings.description' => ['nullable', 'string'],
            'settings.primary_color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'settings.secondary_color' => ['required', 'string', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'settings.emails' => ['required', 'string'],
            'settings.phone' => ['required', 'string'],
            'settings.address' => ['nullable', 'string'],
            'settings.latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'settings.longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'settings.facebook' => ['nullable', 'url'],
            'settings.instagram' => ['nullable', 'url'],
            'settings.twitter' => ['nullable', 'url'],
            'settings.youtube' => ['nullable', 'url'],
            'settings.whatsapp' => ['nullable', 'string'],
            'settings.currency_name_en' => ['required', 'string', 'max:255'],
            'settings.currency_name_ar' => ['required', 'string', 'max:255'],
            'settings.currency_symbol_en' => ['required', 'string', 'max:10'],
            'settings.currency_symbol_ar' => ['required', 'string', 'max:10'],
            'settings.tax_rate' => ['required', 'numeric', 'min:0', 'max:100'],
            'settings.default_payment_terms' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($validated['settings'] as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
