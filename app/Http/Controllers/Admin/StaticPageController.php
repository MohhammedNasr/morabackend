<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function index()
    {
        $pages = StaticPage::all();
        return view('admin.static-pages.index', compact('pages'));
    }

    public function edit($id)
    {
        $page = StaticPage::findOrFail($id);
        return view('admin.static-pages.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $page = StaticPage::findOrFail($id);

        $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'details_en' => 'required|string',
            'details_ar' => 'required|string'
        ]);

        $page->update($request->all());

        return redirect()->route('admin.static-pages.index')
            ->with('success', 'Static page updated successfully');
    }
}
