<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Project;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    public function index()
    {
        $amenities = Amenity::with('project')->latest()->paginate(20);
        return view('admin.amenities.index', compact('amenities'));
    }

    public function create()
    {
        $projects = Project::active()->get();
        return view('admin.amenities.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        Amenity::create($validated);
        return redirect()->route('admin.amenities.index')->with('success', 'Amenity created.');
    }

    public function edit(Amenity $amenity)
    {
        $projects = Project::active()->get();
        return view('admin.amenities.edit', compact('amenity', 'projects'));
    }

    public function update(Request $request, Amenity $amenity)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $amenity->update($validated);
        return redirect()->route('admin.amenities.index')->with('success', 'Amenity updated.');
    }

    public function destroy(Amenity $amenity)
    {
        $amenity->delete();
        return redirect()->route('admin.amenities.index')->with('success', 'Amenity deleted.');
    }
}
