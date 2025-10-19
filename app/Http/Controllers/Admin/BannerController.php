<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Http\Requests\Admin\BannerRequest;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::latest()->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(BannerRequest $request)
    {
        $data = $request->validated();

        // Store image and map fields correctly
        $bannerData = [
            'image' => $request->file('image')->store('images/banners', 'public'),
            'link' => $data['link'] ?? null,
            'is_active' => $data['is_active']
        ];
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/banners'), $imageName);
            $bannerData['image'] = 'images/banners/' . $imageName;
        }

        Banner::create($bannerData);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner created successfully.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(BannerRequest $request, Banner $banner)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($data['image'] && file_exists(public_path($data['image']))) {
                unlink(public_path($data['image']));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/banners'), $imageName);
            $data['image'] = 'images/banners/' . $imageName;
        }
        $banner->update($data);

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        // Delete image
        Storage::disk('public')->delete($banner->image);

        $banner->delete();
        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner deleted successfully.');
    }
}
