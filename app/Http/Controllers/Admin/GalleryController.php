<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Project;
use App\Services\UploadService;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    protected UploadService $upload;

    public function __construct(UploadService $upload)
    {
        $this->upload = $upload;
    }

    public function index()
    {
        $galleries = Gallery::with('project')->latest()->paginate(20);
        return view('admin.gallery.index', compact('galleries'));
    }

    public function create()
    {
        $projects = Project::active()->get();
        return view('admin.gallery.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'category' => 'required|in:general,interior,exterior,amenities,site',
            'project_id' => 'nullable|exists:projects,id',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $validated['image'] = $this->upload->upload($request->file('image'), 'gallery');
        Gallery::create($validated);
        return redirect()->route('admin.gallery.index')->with('success', 'Gallery image added.');
    }

    public function edit(Gallery $gallery)
    {
        $projects = Project::active()->get();
        return view('admin.gallery.edit', compact('gallery', 'projects'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'category' => 'required|in:general,interior,exterior,amenities,site',
            'project_id' => 'nullable|exists:projects,id',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($request->hasFile('image')) {
            $this->upload->delete($gallery->image);
            $validated['image'] = $this->upload->upload($request->file('image'), 'gallery');
        }

        $gallery->update($validated);
        return redirect()->route('admin.gallery.index')->with('success', 'Gallery image updated.');
    }

    public function destroy(Gallery $gallery)
    {
        $this->upload->delete($gallery->image);
        $gallery->delete();
        return redirect()->route('admin.gallery.index')->with('success', 'Gallery image deleted.');
    }
}
