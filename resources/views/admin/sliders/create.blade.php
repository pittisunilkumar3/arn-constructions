@extends('admin.layout')
@section('title', 'Slider Form')
@section('page-title', '{{ isset($slider) ? "Edit" : "Add" }} Slider')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ isset($slider) ? 'Edit' : 'Add' }} Slider</h4>
    <a href="{{ route('admin.sliders.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back</a>
</div>
<div class="form-card">
    <form action="{{ isset($slider) ? route('admin.sliders.update', $slider) : route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data">
        @csrf @if(isset($slider)) @method('PUT') @endif
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Title *</label><input type="text" name="title" class="form-control" value="{{ old('title', isset($slider) ? $slider->title : '') }}" required></div>
            <div class="col-md-6"><label class="form-label">Subtitle</label><input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', isset($slider) ? $slider->subtitle : '') }}"></div>
            <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3">{{ old('description', isset($slider) ? $slider->description : '') }}</textarea></div>
            <div class="col-md-6"><label class="form-label">Image {{ isset($slider) ? '' : '*' }}</label>
                @if(isset($slider) && $slider->image)<div class="mb-2"><img src="{{ upload_url($slider->image) }}" style="max-height:100px;" class="rounded"></div>@endif
                <input type="file" name="image" class="form-control" accept="image/*" {{ isset($slider) ? '' : 'required' }}>
            </div>
            <div class="col-md-6">
                <div class="row g-3">
                    <div class="col-6"><label class="form-label">Button Text</label><input type="text" name="button_text" class="form-control" value="{{ old('button_text', isset($slider) ? $slider->button_text : '') }}" placeholder="e.g., Explore"></div>
                    <div class="col-6"><label class="form-label">Button Link</label><input type="text" name="button_link" class="form-control" value="{{ old('button_link', isset($slider) ? $slider->button_link : '') }}" placeholder="/projects"></div>
                    <div class="col-6"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', isset($slider) ? $slider->sort_order : 0) }}"></div>
                    <div class="col-6"><div class="form-check mt-4"><input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', isset($slider) ? $slider->is_active : true) ? 'checked' : '' }}><label class="form-check-label">Active</label></div></div>
                </div>
            </div>
            <div class="col-12 mt-3"><button type="submit" class="btn btn-sm-primary px-4"><i class="fas fa-save me-2"></i>{{ isset($slider) ? 'Update' : 'Save' }}</button></div>
        </div>
    </form>
</div>
@endsection
