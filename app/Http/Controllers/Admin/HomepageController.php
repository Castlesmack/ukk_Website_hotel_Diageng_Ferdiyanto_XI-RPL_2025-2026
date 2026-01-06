<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function edit()
    {
        // Get homepage settings from session or database
        $homepageData = session()->get('homepage_data', [
            'sliders' => [],
            'facilities' => [
                ['id' => 1, 'name' => 'Free WiFi', 'icon' => '📡'],
                ['id' => 2, 'name' => 'Swimming Pool', 'icon' => '🏊'],
                ['id' => 3, 'name' => 'Kitchen', 'icon' => '🍳'],
                ['id' => 4, 'name' => 'Air Conditioning', 'icon' => '❄️'],
            ],
            'description' => 'Welcome to Ade Villa - Your perfect vacation destination'
        ]);

        return view('admin.homepage.edit', compact('homepageData'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'description' => 'nullable|string',
            'facilities.*.name' => 'required|string',
            'facilities.*.icon' => 'nullable|string',
        ]);

        $homepageData = [
            'description' => $request->input('description'),
            'facilities' => $request->input('facilities', []),
            'sliders' => $request->input('sliders', []),
        ];

        session()->put('homepage_data', $homepageData);

        return redirect()->route('admin.homepage.edit')->with('success', 'Homepage updated successfully.');
    }
}
