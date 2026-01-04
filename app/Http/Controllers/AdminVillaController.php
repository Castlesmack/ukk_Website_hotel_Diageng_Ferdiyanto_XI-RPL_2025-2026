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
        ]);

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;
        while (Villa::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        Villa::create([
            'name' => $request->name,
            'slug' => $slug,
            'capacity' => $request->capacity,
            'base_price' => $request->base_price,
            'rooms_total' => $request->rooms_total,
            'description' => $request->description,
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->route('admin.manage')->with('success', 'Villa created successfully.');
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
        ]);

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;
        while (Villa::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $villa->update([
            'name' => $request->name,
            'slug' => $slug,
            'capacity' => $request->capacity,
            'base_price' => $request->base_price,
            'rooms_total' => $request->rooms_total,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.manage')->with('success', 'Villa updated successfully.');
    }

    public function destroy($id)
    {
        $villa = Villa::findOrFail($id);
        $villa->delete();

        return redirect()->route('admin.manage')->with('success', 'Villa deleted successfully.');
    }
}