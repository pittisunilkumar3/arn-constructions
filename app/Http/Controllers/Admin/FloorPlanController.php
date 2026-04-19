<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FloorPlan;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FloorPlanController extends Controller
{
    public function index()
    {
        $floorPlans = FloorPlan::with('project')->latest()->paginate(15);
        return view('admin.floor-plans.index', compact('floorPlans'));
    }

    public function create()
    {
        $projects = Project::active()->get();
        return view('admin.floor-plans.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'bhk_type' => 'required|string|max:50',
            'area_sqft' => 'required|numeric',
            'price' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('floor-plans', 'public');
        }

        FloorPlan::create($validated);
        return redirect()->route('admin.floor-plans.index')->with('success', 'Floor plan created.');
    }

    public function edit(FloorPlan $floorPlan)
    {
        $projects = Project::active()->get();
        return view('admin.floor-plans.edit', compact('floorPlan', 'projects'));
    }

    public function update(Request $request, FloorPlan $floorPlan)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string|max:255',
            'bhk_type' => 'required|string|max:50',
            'area_sqft' => 'required|numeric',
            'price' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($request->hasFile('image')) {
            if ($floorPlan->image) Storage::disk('public')->delete($floorPlan->image);
            $validated['image'] = $request->file('image')->store('floor-plans', 'public');
        }

        $floorPlan->update($validated);
        return redirect()->route('admin.floor-plans.index')->with('success', 'Floor plan updated.');
    }

    public function destroy(FloorPlan $floorPlan)
    {
        if ($floorPlan->image) Storage::disk('public')->delete($floorPlan->image);
        $floorPlan->delete();
        return redirect()->route('admin.floor-plans.index')->with('success', 'Floor plan deleted.');
    }
}
