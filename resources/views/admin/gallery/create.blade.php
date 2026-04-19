@extends('admin.layout')
@section('title', 'Add Gallery Image')
@section('page-title', '{{ isset($gallery) ? "Edit" : "Add" }} Gallery Image')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ isset($gallery) ? 'Edit' : 'Add' }} Gallery Image</h4>
    <a href="{{ route('admin.gallery.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back</a>
</div>
<div class="form-card">
    <form action="{{ isset($gallery) ? route('admin.gallery.update', $gallery) : route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
        @csrf @if(isset($gallery)) @method('PUT') @endif
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Title</label><input type="text" name="title" class="form-control" value="{{ old('title', isset($gallery) ? $gallery->title : '') }}"></div>
            <div class="col-md-6"><label class="form-label">Category *</label>
                <select name="category" class="form-select" required>
                    @foreach(['general','interior','exterior','amenities','site'] as $cat)
                    <option value="{{ $cat }}" {{ old('category', isset($gallery) ? $gallery->category : '') == $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6"><label class="form-label">Project</label>
                <select name="project_id" class="form-select"><option value="">All Projects</option>@foreach($projects as $p)<option value="{{ $p->id }}" {{ old('project_id', isset($gallery) ? $gallery->project_id : '') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>@endforeach</select>
            </div>
            <div class="col-md-6"><label class="form-label">Image {{ isset($gallery) ? '' : '*' }}</label>
                @if(isset($gallery) && $gallery->image)<div class="mb-2"><img src="{{ upload_url($gallery->image) }}" style="max-height:100px;" class="rounded"></div>@endif
                <input type="file" name="image" class="form-control" accept="image/*" {{ isset($gallery) ? '' : 'required' }}>
            </div>
            <div class="col-md-3"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', isset($gallery) ? $gallery->sort_order : 0) }}"></div>
            <div class="col-md-3"><div class="form-check mt-4"><input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', isset($gallery) ? $gallery->is_active : true) ? 'checked' : '' }}><label class="form-check-label">Active</label></div></div>
            <div class="col-12 mt-3"><button type="submit" class="btn btn-sm-primary px-4"><i class="fas fa-save me-2"></i>{{ isset($gallery) ? 'Update' : 'Save' }}</button></div>
        </div>
    </form>
</div>
@endsection
