<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Services\UploadService;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    protected UploadService $upload;

    public function __construct(UploadService $upload)
    {
        $this->upload = $upload;
    }

    public function index()
    {
        $testimonials = Testimonial::with('project')->latest()->paginate(10);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        $projects = \App\Models\Project::active()->get();
        return view('admin.testimonials.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'testimonial' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'rating' => 'required|integer|min:1|max:5',
            'project_id' => 'nullable|exists:projects,id',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->upload->upload($request->file('image'), 'testimonials');
        }

        Testimonial::create($validated);
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial created.');
    }

    public function edit(Testimonial $testimonial)
    {
        $projects = \App\Models\Project::active()->get();
        return view('admin.testimonials.edit', compact('testimonial', 'projects'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'designation' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'testimonial' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'rating' => 'required|integer|min:1|max:5',
            'project_id' => 'nullable|exists:projects,id',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($request->hasFile('image')) {
            if ($testimonial->image) {
                $this->upload->delete($testimonial->image);
            }
            $validated['image'] = $this->upload->upload($request->file('image'), 'testimonials');
        }

        $testimonial->update($validated);
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->image) {
            $this->upload->delete($testimonial->image);
        }
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial deleted.');
    }
}
