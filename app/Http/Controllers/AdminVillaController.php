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
        // Validate input
        $request->validate([
            'name' => 'required|string|max:191',
            'capacity' => 'required|integer|min:1',
            'base_price' => 'required|numeric|min:0',
            'rooms_total' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,maintenance',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,gif,jpg|max:20480',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,gif,jpg|max:20480',
        ]);

        // Ensure upload directory exists
        $uploadDir = public_path('uploads/villas');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique slug
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;
        while (Villa::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Handle thumbnail
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = 'thumb_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $thumbnailPath = 'uploads/villas/' . $filename;
        }

        // Handle gallery images
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = 'img_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadDir, $filename);
                $imagePaths[] = 'uploads/villas/' . $filename;
            }
        }

        // Create villa - store null if no images, otherwise array
        $villaData = [
            'name' => $request->name,
            'slug' => $slug,
            'capacity' => $request->capacity,
            'base_price' => $request->base_price,
            'rooms_total' => $request->rooms_total,
            'description' => $request->description,
            'status' => $request->status,
            'thumbnail_path' => $thumbnailPath,
            'closed_dates' => [],
        ];

        // Only add images if there are any - FORCE JSON ENCODING
        if (count($imagePaths) > 0) {
            $villaData['images'] = json_encode($imagePaths);
        } else {
            $villaData['images'] = null;
        }

        $villa = Villa::create($villaData);

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

        // Validate input
        $request->validate([
            'name' => 'required|string|max:191',
            'capacity' => 'required|integer|min:1',
            'base_price' => 'required|numeric|min:0',
            'rooms_total' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,maintenance',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,gif,jpg|max:20480',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,gif,jpg|max:20480',
        ]);

        // Ensure upload directory exists
        $uploadDir = public_path('uploads/villas');
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Update slug
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;
        while (Villa::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Handle thumbnail update
        $thumbnailPath = $villa->thumbnail_path;
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($thumbnailPath && file_exists(public_path($thumbnailPath))) {
                unlink(public_path($thumbnailPath));
            }
            // Save new thumbnail
            $file = $request->file('thumbnail');
            $filename = 'thumb_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $filename);
            $thumbnailPath = 'uploads/villas/' . $filename;
        }

        // Handle gallery images - start with existing
        $imagePaths = is_array($villa->images) ? $villa->images : ($villa->images ? json_decode($villa->images, true) : []);
        
        // Delete marked images
        if ($request->has('delete_images') && is_array($request->delete_images)) {
            foreach ($request->delete_images as $pathToDelete) {
                // Remove from array
                $key = array_search($pathToDelete, $imagePaths);
                if ($key !== false) {
                    unset($imagePaths[$key]);
                }
                // Delete file
                if (file_exists(public_path($pathToDelete))) {
                    unlink(public_path($pathToDelete));
                }
            }
            // Re-index array
            $imagePaths = array_values($imagePaths);
        }
        
        // Add new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $filename = 'img_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move($uploadDir, $filename);
                $imagePaths[] = 'uploads/villas/' . $filename;
            }
        }

        // Update villa
        $updateData = [
            'name' => $request->name,
            'slug' => $slug,
            'capacity' => $request->capacity,
            'base_price' => $request->base_price,
            'rooms_total' => $request->rooms_total,
            'description' => $request->description,
            'status' => $request->status,
            'thumbnail_path' => $thumbnailPath,
        ];

        // Only add images if there are any - FORCE JSON ENCODING
        if (count($imagePaths) > 0) {
            $updateData['images'] = json_encode($imagePaths);
        } else {
            $updateData['images'] = null;
        }

        $villa->update($updateData);

        return redirect()->route('admin.villas.index')->with('success', 'Villa updated successfully!');
    }

    public function destroy($id)
    {
        $villa = Villa::findOrFail($id);

        // Delete thumbnail
        if ($villa->thumbnail_path && file_exists(public_path($villa->thumbnail_path))) {
            unlink(public_path($villa->thumbnail_path));
        }

        // Delete all gallery images
        if ($villa->images && is_array($villa->images)) {
            foreach ($villa->images as $imagePath) {
                if (file_exists(public_path($imagePath))) {
                    unlink(public_path($imagePath));
                }
            }
        }

        $villa->delete();
        return redirect()->route('admin.villas.index')->with('success', 'Villa deleted successfully!');
    }
}
