<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Villa;
use Illuminate\Http\Request;

class VillaController extends Controller
{
    public function index()
    {
        $villas = Villa::paginate(15);
        return view('admin.villas.index', compact('villas'));
    }

    public function create()
    {
        return view('admin.villas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:villas,slug',
            'capacity' => 'required|integer|min:1',
            'base_price' => 'required|numeric|min:0',
            'rooms_total' => 'required|integer|min:1',
            'description' => 'required|string',
            'status' => 'required|in:available,unavailable',
        ]);

        Villa::create($validated);

        return redirect()->route('admin.villas.index')
            ->with('success', 'Villa created successfully!');
    }

    public function edit(Villa $villa)
    {
        return view('admin.villas.edit', compact('villa'));
    }

    public function update(Request $request, Villa $villa)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:villas,slug,' . $villa->id,
            'capacity' => 'required|integer|min:1',
            'base_price' => 'required|numeric|min:0',
            'rooms_total' => 'required|integer|min:1',
            'description' => 'required|string',
            'status' => 'required|in:available,unavailable',
        ]);

        $villa->update($validated);

        return redirect()->route('admin.villas.index')
            ->with('success', 'Villa updated successfully!');
    }

    public function destroy(Villa $villa)
    {
        $villa->delete();
        return redirect()->route('admin.villas.index')
            ->with('success', 'Villa deleted successfully!');
    }
}
