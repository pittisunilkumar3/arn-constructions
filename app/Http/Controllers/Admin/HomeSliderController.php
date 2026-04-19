<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSlider;
use App\Services\UploadService;
use Illuminate\Http\Request;

class HomeSliderController extends Controller
{
    protected UploadService $upload;

    public function __construct(UploadService $upload)
    {
        $this->upload = $upload;
    }

    public function index()
    {
        $sliders = HomeSlider::orderBy('sort_order')->paginate(10);
        return view('admin.sliders.index', compact('sliders'));
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $validated['image'] = $this->upload->upload($request->file('image'), 'sliders');
        HomeSlider::create($validated);
        return redirect()->route('admin.sliders.index')->with('success', 'Slider created.');
    }

    public function edit(HomeSlider $slider)
    {
        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, HomeSlider $slider)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'button_text' => 'nullable|string|max:50',
            'button_link' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if ($request->hasFile('image')) {
            $this->upload->delete($slider->image);
            $validated['image'] = $this->upload->upload($request->file('image'), 'sliders');
        }

        $slider->update($validated);
        return redirect()->route('admin.sliders.index')->with('success', 'Slider updated.');
    }

    public function destroy(HomeSlider $slider)
    {
        $this->upload->delete($slider->image);
        $slider->delete();
        return redirect()->route('admin.sliders.index')->with('success', 'Slider deleted.');
    }
}
