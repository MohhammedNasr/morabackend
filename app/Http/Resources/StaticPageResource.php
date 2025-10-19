<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;

class StaticPageResource extends JsonResource
{
    public function toArray($request)
    {
        $isArabic = App::getLocale() === 'ar';

        return [
            'id' => $this->id,
            // 'title_en' => $this->title_en,
            // 'title_ar' => $this->title_ar,
            // 'details_en' => $this->details_en,
            // 'details_ar' => $this->details_ar,
            'title' => $isArabic ? $this->title_ar : $this->title_en,
            'details' => $isArabic ? $this->details_ar : $this->details_en,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
