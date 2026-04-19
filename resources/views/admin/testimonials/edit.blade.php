@extends('admin.layout')
@section('title', 'Edit Testimonial')
@section('page-title', 'Edit Testimonial')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Edit Testimonial</h4>
    <a href="{{ route('admin.testimonials.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back</a>
</div>
<div class="form-card">
    <form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" value="{{ old('name', $testimonial->name) }}" required></div>
            <div class="col-md-6"><label class="form-label">Designation</label><input type="text" name="designation" class="form-control" value="{{ old('designation', $testimonial->designation) }}"></div>
            <div class="col-md-6"><label class="form-label">Company</label><input type="text" name="company" class="form-control" value="{{ old('company', $testimonial->company) }}"></div>
            <div class="col-md-6"><label class="form-label">Rating *</label>
                <select name="rating" class="form-select" required>
                    @for($i=1;$i<=5;$i++)<option value="{{ $i }}" {{ old('rating', $testimonial->rating) == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i>1?'s':'' }}</option>@endfor
                </select>
            </div>
            <div class="col-md-6"><label class="form-label">Project</label>
                <select name="project_id" class="form-select"><option value="">None</option>@foreach($projects as $p)<option value="{{ $p->id }}" {{ old('project_id', $testimonial->project_id) == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>@endforeach</select>
            </div>
            <div class="col-md-6"><label class="form-label">Photo</label>
                @if($testimonial->image)<div class="mb-2"><img src="{{ Storage::url($testimonial->image) }}" style="max-height:80px;" class="rounded"></div>@endif
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <div class="col-12"><label class="form-label">Testimonial *</label><textarea name="testimonial" class="form-control" rows="4" required>{{ old('testimonial', $testimonial->testimonial) }}</textarea></div>
            <div class="col-md-4"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $testimonial->sort_order) }}"></div>
            <div class="col-md-4"><div class="form-check mt-3"><input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', $testimonial->is_active) ? 'checked' : '' }}><label class="form-check-label">Active</label></div></div>
            <div class="col-12 mt-3"><button type="submit" class="btn btn-sm-primary px-4"><i class="fas fa-save me-2"></i>Update</button></div>
        </div>
    </form>
</div>
@endsection
