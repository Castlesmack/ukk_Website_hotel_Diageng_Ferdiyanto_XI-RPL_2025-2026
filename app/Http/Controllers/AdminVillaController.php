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
        $request->validate([
            'name' => 'required|string|max:191',
            'capacity' => 'required|integer|min:1',
            'base_price' => 'required|numeric|min:0',
            'rooms_total' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,maintenance',
            'thumbnail' => 'nullable|image|max:5120',
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',
        ]);

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;
        while (Villa::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        // Create uploads directory if it doesn't exist
        if (!is_dir(public_path('uploads/villas'))) {
            mkdir(public_path('uploads/villas'), 0755, true);
        }

        // Handle thumbnail upload
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $filename = time() . '_thumbnail_' . Str::random(10) . '.' . $request->file('thumbnail')->extension();
            $request->file('thumbnail')->move(public_path('uploads/villas'), $filename);
            $thumbnailPath = 'uploads/villas/' . $filename;
        }

        // Handle gallery images upload
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . Str::random(10) . '.' . $image->extension();
                $image->move(public_path('uploads/villas'), $filename);
                $imagePaths[] = 'uploads/villas/' . $filename;
            }
        }

        Villa::create([
            'name' => $request->name,
            'slug' => $slug,
            'capacity' => $request->capacity,
            'base_price' => $request->base_price,
            'rooms_total' => $request->rooms_total,
            'description' => $request->description,
            'status' => $request->status ?? 'active',
            'thumbnail_path' => $thumbnailPath,
            'images' => $imagePaths,
            'closed_dates' => [],
        ]);

        return redirect()->route('admin.villas.index')->with('success', 'Villa created successfully.');
    }

    public function edit($id)
    {
        $villa = Villa::findOrFail($id);
        return view('admin.villas.edit', compact('villa'));
    }

    public function update(Request $request, $id)
    {
        $villa = Villa::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:191',
            'capacity' => 'required|integer|min:1',
            'base_price' => 'required|numeric|min:0',
            'rooms_total' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,maintenance',
            'thumbnail' => 'nullable|image|max:5120',
            'images' => 'nullable|array',
            'images.*' => 'image|max:5120',
            'closed_dates' => 'nullable|json',
        ]);

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;
        while (Villa::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        // Create uploads directory if it doesn't exist
        if (!is_dir(public_path('uploads/villas'))) {
            mkdir(public_path('uploads/villas'), 0755, true);
        }

        // Handle thumbnail upload
        $thumbnailPath = $villa->thumbnail_path;
        if ($request->hasFile('thumbnail')) {
            if ($thumbnailPath && file_exists(public_path($thumbnailPath))) {
                unlink(public_path($thumbnailPath));
            }
            $filename = time() . '_thumbnail_' . Str::random(10) . '.' . $request->file('thumbnail')->extension();
            $request->file('thumbnail')->move(public_path('uploads/villas'), $filename);
            $thumbnailPath = 'uploads/villas/' . $filename;
        }

        // Handle gallery images upload
        $imagePaths = $villa->images ?? [];
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . Str::random(10) . '.' . $image->extension();
                $image->move(public_path('uploads/villas'), $filename);
                $imagePaths[] = 'uploads/villas/' . $filename;
            }
        }

        $closedDates = [];
        if ($request->closed_dates) {
            $closedDates = json_decode($request->closed_dates, true) ?? [];
        }

        $villa->update([
            'name' => $request->name,
            'slug' => $slug,
            'capacity' => $request->capacity,
            'base_price' => $request->base_price,
            'rooms_total' => $request->rooms_total,
            'description' => $request->description,
            'status' => $request->status,
            'thumbnail_path' => $thumbnailPath,
            'images' => $imagePaths,
            'closed_dates' => $closedDates,
        ]);

        return redirect()->route('admin.villas.index')->with('success', 'Villa updated successfully.');
    }

    public function destroy($id)
    {
        $villa = Villa::findOrFail($id);
        
        // Delete thumbnail
        if ($villa->thumbnail_path && file_exists(public_path($villa->thumbnail_path))) {
            unlink(public_path($villa->thumbnail_path));
        }
        
        // Delete gallery images
        if ($villa->images && is_array($villa->images)) {
            foreach ($villa->images as $image) {
                if (file_exists(public_path($image))) {
                    unlink(public_path($image));
                }
            }
        }
        
        $villa->delete();
        return redirect()->route('admin.villas.index')->with('success', 'Villa deleted successfully.');
    }
}