<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\StaticPageResource;
use App\Models\StaticPage;

class StaticPageController extends Controller
{

    public function termsAndConditions()
    {
        $page = StaticPage::findOrFail(1);
        return new StaticPageResource($page);
    }

    public function show($id)
    {
        $page = StaticPage::findOrFail($id);
        return new StaticPageResource($page);
    }
}
