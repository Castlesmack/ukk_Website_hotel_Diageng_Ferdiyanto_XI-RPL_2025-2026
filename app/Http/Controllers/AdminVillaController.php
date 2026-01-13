<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Villa;
use Illuminate\Support\Str;

class AdminVillaController extends Controller
{
    public function index()
    {
        $villas = Villa::all();
        return view('admin.villas.index', compact('villas'));
    }

    public function create()
    {
        return view('admin.villas.create');
    }

    public function store(Request $request)
    {
        // Validate - keep file size small for now
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'capacity' => 'required|integer|min:1',
            'base_price' => 'required|numeric|min:0',
            'rooms_total' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,maintenance',
            'thumbnail' => 'nullable|image|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
        ]);

        // Generate slug
        $slug = Str::slug($request->name);
        $counter = 1;
        while (Villa::where('slug', $slug)->exists()) {
            $slug = Str::slug($request->name) . '-' . $counter;
            $counter++;
        }

        // Ensure upload directory
        $uploadDir = public_path('uploads/villas');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Upload thumbnail
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumb = $request->file('thumbnail');
            $filename = 'thumb_' . time() . '_' . Str::random(8) . '.' . $thumb->extension();
            $thumb->move($uploadDir, $filename);
            $thumbnailPath = 'uploads/villas/' . $filename;
        }

        // Upload gallery images
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = 'img_' . time() . '_' . uniqid() . '.' . $file->extension();
                $file->move($uploadDir, $filename);
                $images[] = 'uploads/villas/' . $filename;
            }
        }

        // Create villa
        Villa::create([
            'name' => $request->name,
            'slug' => $slug,
            'capacity' => $request->capacity,
            'base_price' => $request->base_price,
            'rooms_total' => $request->rooms_total,
            'description' => $request->description,
            'status' => $request->status,
            'thumbnail_path' => $thumbnailPath,
            'images' => count($images) > 0 ? $images : [],
            'closed_dates' => [],
        ]);

        return redirect()->route('admin.villas.index')->with('success', 'Villa created successfully!');
    }

    public function edit($id)
    {
        $villa = Villa::findOrFail($id);
        return view('admin.villas.edit', compact('villa'));
    }

    public function update(Request $request, $id)
    {
        $villa = Villa::findOrFail($id);

        // Validate
        $request->validate([
            'name' => 'required|string|max:191',
            'capacity' => 'required|integer|min:1',
            'base_price' => 'required|numeric|min:0',
            'rooms_total' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,maintenance',
            'thumbnail' => 'nullable|image|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
        ]);

        // Generate slug
        $slug = Str::slug($request->name);
        $counter = 1;
        while (Villa::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = Str::slug($request->name) . '-' . $counter;
            $counter++;
        }

        // Ensure upload directory
        $uploadDir = public_path('uploads/villas');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Handle thumbnail
        $thumbnailPath = $villa->thumbnail_path;
        if ($request->hasFile('thumbnail')) {
            // Delete old
            if ($thumbnailPath && file_exists(public_path($thumbnailPath))) {
                unlink(public_path($thumbnailPath));
            }
            // Upload new
            $thumb = $request->file('thumbnail');
            $filename = 'thumb_' . time() . '_' . Str::random(8) . '.' . $thumb->extension();
            $thumb->move($uploadDir, $filename);
            $thumbnailPath = 'uploads/villas/' . $filename;
        }

        // Handle images
        $images = is_array($villa->images) ? $villa->images : json_decode($villa->images, true) ?? [];

        // Delete marked images
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $path) {
                $key = array_search($path, $images);
                if ($key !== false) {
                    unset($images[$key]);
                    if (file_exists(public_path($path))) {
                        unlink(public_path($path));
                    }
                }
            }
            $images = array_values($images);
        }

        // Add new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = 'img_' . time() . '_' . uniqid() . '.' . $file->extension();
                $file->move($uploadDir, $filename);
                $images[] = 'uploads/villas/' . $filename;
            }
        }

        // Update villa
        $villa->update([
            'name' => $request->name,
            'slug' => $slug,
            'capacity' => $request->capacity,
            'base_price' => $request->base_price,
            'rooms_total' => $request->rooms_total,
            'description' => $request->description,
            'status' => $request->status,
            'thumbnail_path' => $thumbnailPath,
            'images' => count($images) > 0 ? $images : [],
        ]);

        return redirect()->route('admin.villas.index')->with('success', 'Villa updated successfully!');
    }

    public function destroy($id)
    {
        $villa = Villa::findOrFail($id);

        // Delete thumbnail
        if ($villa->thumbnail_path && file_exists(public_path($villa->thumbnail_path))) {
            unlink(public_path($villa->thumbnail_path));
        }

        // Delete images
        if ($villa->images) {
            $images = json_decode($villa->images, true) ?? [];
            foreach ($images as $image) {
                if (file_exists(public_path($image))) {
                    unlink(public_path($image));
                }
            }
        }

        $villa->delete();
        return redirect()->route('admin.villas.index')->with('success', 'Villa deleted successfully!');
    }
}
