<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Get current settings
     */
    public function index()
    {
    return $this->settingService->getSettings();
    }
    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            // Add validation rules for settings fields
        ]);

        $settings = $this->settingService->updateSettings($validated);
        return response()->json($settings);
    }
}
