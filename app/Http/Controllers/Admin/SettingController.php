<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSetting;
use App\Models\VillaVisibility;
use App\Models\HomepageFacility;
use App\Models\Villa;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function editHomepage()
    {
        $homepage = HomepageSetting::first() ?? new HomepageSetting();
        $villas = Villa::all();
        $villaVisibility = VillaVisibility::orderBy('order')->get()->keyBy('villa_id');
        $facilities = HomepageFacility::orderBy('category')->orderBy('order')->get()->groupBy('category');
        
        return view('admin.settings.homepage-edit', compact('homepage', 'villas', 'villaVisibility', 'facilities'));
    }

    public function updateHomepage(Request $request)
    {
        $request->validate([
            'description' => 'nullable|string',
            'slider_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'villa_order' => 'nullable|array',
            'villa_visible' => 'nullable|array',
            'facilities' => 'nullable|array',
        ]);

        // Get or create homepage settings
        $homepage = HomepageSetting::first() ?? new HomepageSetting();
        $homepage->description = $request->input('description');

        // Handle slider images
        $images = $homepage->slider_images ?? [];
        
        if ($request->hasFile('slider_images')) {
            // Keep only max 5 images
            $images = array_slice($images, 0, 4); // Current images (max 4 to allow 1 more)
            
            foreach ($request->file('slider_images') as $file) {
                if (count($images) >= 5) break;
                // Store directly in public uploads directory, not in storage
                $uploadDir = public_path('uploads/homepage');
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $filename = 'slider_' . time() . '_' . uniqid() . '.' . $file->extension();
                $file->move($uploadDir, $filename);
                $images[] = 'uploads/homepage/' . $filename;
            }
        }
        
        // Handle image deletion
        if ($request->has('delete_image')) {
            $deleteIndex = $request->input('delete_image');
            if (isset($images[$deleteIndex])) {
                array_splice($images, $deleteIndex, 1);
            }
        }

        $homepage->slider_images = array_values($images); // Re-index array
        $homepage->save();

        // Update villa visibility and order
        if ($request->has('villa_visible')) {
            $order = 0;
            foreach ($request->input('villa_visible', []) as $villaId) {
                VillaVisibility::updateOrCreate(
                    ['villa_id' => $villaId],
                    ['is_visible' => true, 'order' => $order++]
                );
            }
            
            // Mark all other villas as invisible
            Villa::whereNotIn('id', $request->input('villa_visible', []))->each(function($villa) {
                VillaVisibility::updateOrCreate(
                    ['villa_id' => $villa->id],
                    ['is_visible' => false]
                );
            });
        }

        // Update facilities visibility
        if ($request->has('facilities')) {
            foreach ($request->input('facilities') as $facilityId => $data) {
                HomepageFacility::where('id', $facilityId)->update([
                    'is_visible' => isset($data['visible'])
                ]);
            }
        }

        return redirect()->back()->with('success', 'Homepage berhasil diperbarui!');
    }

    public function deleteHomepageImage(Request $request)
    {
        $request->validate([
            'image_index' => 'required|integer|min:0',
        ]);

        $homepage = HomepageSetting::first();
        if (!$homepage) {
            return redirect()->back()->with('error', 'Homepage settings tidak ditemukan!');
        }

        $images = $homepage->slider_images ?? [];
        $imageIndex = $request->input('image_index');

        if (isset($images[$imageIndex])) {
            // Delete the file from disk
            $filePath = public_path($images[$imageIndex]);
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            
            // Remove from array and re-index
            array_splice($images, $imageIndex, 1);
            $homepage->slider_images = array_values($images);
            $homepage->save();

            return redirect()->back()->with('success', 'Gambar slider berhasil dihapus!');
        }

        return redirect()->back()->with('error', 'Gambar tidak ditemukan!');
    }

    public function manageFacilities()
    {
        $facilities = HomepageFacility::all();
        return view('admin.settings.facilities', compact('facilities'));
    }

    public function storeFacility(Request $request)
    {
        $request->validate([
            'category' => 'required|string',
            'name' => 'required|string',
            'icon' => 'nullable|string|max:10',
        ]);

        $icon = $request->input('icon', '✨');
        
        HomepageFacility::create([
            'category' => $request->input('category'),
            'name' => $request->input('name'),
            'icon' => $icon,
            'is_visible' => true,
            'order' => HomepageFacility::where('category', $request->input('category'))->max('order') + 1,
        ]);

        return redirect()->back()->with('success', 'Fasilitas berhasil ditambahkan!');
    }

    public function destroyFacility(HomepageFacility $facility)
    {
        $facility->delete();
        return redirect()->back()->with('success', 'Fasilitas berhasil dihapus!');
    }

    public function villaGallery()
    {
        return view('admin.settings.gallery');
    }
}
