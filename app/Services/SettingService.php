<?php

namespace App\Services;

use App\Http\Resources\SettingResource;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService extends BaseService
{
    public function getSettings()
    {
        // return Cache::remember('settings', 3600, function () {
            $settings =  Setting::firstOrFail();
            return $this->successResponse(
                data: new SettingResource($settings),
                message: __('api.settings_retrieved')
            );
    //   /  });
    }

    public function updateSettings(array $data)
    {
        $settings = Setting::firstOrFail();
        $settings->update($data);

        // Clear cache after update
        Cache::forget('settings');

        return $settings;
    }
}
