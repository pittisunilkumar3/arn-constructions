<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    protected UploadService $upload;

    public function __construct(UploadService $upload)
    {
        $this->upload = $upload;
    }

    public function index(Request $request)
    {
        $query = Project::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $projects = $query->latest()->paginate(10);
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:projects',
            'location' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'type' => 'required|in:apartment,villa,plot,commercial',
            'status' => 'required|in:ongoing,completed,upcoming',
            'price_min' => 'nullable|numeric',
            'price_max' => 'nullable|numeric',
            'bhk_options' => 'nullable|string',
            'total_units' => 'nullable|integer',
            'area_min' => 'nullable|numeric',
            'area_max' => 'nullable|numeric',
            'rera_id' => 'nullable|string|max:255',
            'possession_date' => 'nullable|date',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'video_url' => 'nullable|url',
            'brochure' => 'nullable|file|mimes:pdf|max:10240',
            'highlights' => 'nullable|array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $this->upload->upload($request->file('featured_image'), 'projects');
        }

        if ($request->hasFile('brochure')) {
            $validated['brochure'] = $this->upload->upload($request->file('brochure'), 'brochures');
        }

        $galleryImages = [];
        if ($request->hasFile('gallery')) {
            $galleryImages = $this->upload->uploadMultiple($request->file('gallery'), 'projects/gallery');
        }
        $validated['gallery'] = $galleryImages;

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);

        Project::create($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function show(Project $project)
    {
        $project->load(['amenities', 'floorPlans', 'galleries', 'enquiries', 'testimonials']);
        return view('admin.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:projects,slug,' . $project->id,
            'location' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'short_description' => 'required|string',
            'description' => 'required|string',
            'type' => 'required|in:apartment,villa,plot,commercial',
            'status' => 'required|in:ongoing,completed,upcoming',
            'price_min' => 'nullable|numeric',
            'price_max' => 'nullable|numeric',
            'bhk_options' => 'nullable|string',
            'total_units' => 'nullable|integer',
            'area_min' => 'nullable|numeric',
            'area_max' => 'nullable|numeric',
            'rera_id' => 'nullable|string|max:255',
            'possession_date' => 'nullable|date',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'video_url' => 'nullable|url',
            'brochure' => 'nullable|file|mimes:pdf|max:10240',
            'highlights' => 'nullable|array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('featured_image')) {
            $this->upload->delete($project->featured_image);
            $validated['featured_image'] = $this->upload->upload($request->file('featured_image'), 'projects');
        }

        if ($request->hasFile('brochure')) {
            $this->upload->delete($project->brochure);
            $validated['brochure'] = $this->upload->upload($request->file('brochure'), 'brochures');
        }

        $existingGallery = $project->gallery ?? [];
        if ($request->hasFile('gallery')) {
            $newImages = $this->upload->uploadMultiple($request->file('gallery'), 'projects/gallery');
            $existingGallery = array_merge($existingGallery, $newImages);
        }
        $validated['gallery'] = $existingGallery;
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active', true);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $project->update($validated);

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $this->upload->delete($project->featured_image);
        if ($project->gallery) {
            $this->upload->deleteMultiple($project->gallery);
        }
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }

    public function deleteGalleryImage(Project $project, $index)
    {
        $gallery = $project->gallery ?? [];
        if (isset($gallery[$index])) {
            $this->upload->delete($gallery[$index]);
            unset($gallery[$index]);
            $project->update(['gallery' => array_values($gallery)]);
        }
        return response()->json(['success' => true]);
    }
}
