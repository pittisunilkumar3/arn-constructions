@extends('admin.layout')
@section('title', 'Add Amenity')
@section('page-title', 'Add Amenity')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ isset($amenity) ? 'Edit' : 'Add' }} Amenity</h4>
    <a href="{{ route('admin.amenities.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back</a>
</div>
<div class="form-card">
    <form action="{{ isset($amenity) ? route('admin.amenities.update', $amenity) : route('admin.amenities.store') }}" method="POST" enctype="multipart/form-data">
        @csrf @if(isset($amenity)) @method('PUT') @endif
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" value="{{ old('name', isset($amenity) ? $amenity->name : '') }}" required></div>
            <div class="col-md-6"><label class="form-label">Icon Class (FontAwesome)</label><input type="text" name="icon" class="form-control" value="{{ old('icon', isset($amenity) ? $amenity->icon : '') }}" placeholder="e.g., fa-swimming-pool"></div>
            <div class="col-md-6"><label class="form-label">Project</label>
                <select name="project_id" class="form-select"><option value="">All Projects</option>@foreach($projects as $p)<option value="{{ $p->id }}" {{ old('project_id', isset($amenity) ? $amenity->project_id : '') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>@endforeach</select>
            </div>
            <div class="col-md-3"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', isset($amenity) ? $amenity->sort_order : 0) }}"></div>
            <div class="col-md-3"><div class="form-check mt-4"><input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', isset($amenity) ? $amenity->is_active : true) ? 'checked' : '' }}><label class="form-check-label">Active</label></div></div>
            <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3">{{ old('description', isset($amenity) ? $amenity->description : '') }}</textarea></div>
            <div class="col-12 mt-3"><button type="submit" class="btn btn-sm-primary px-4"><i class="fas fa-save me-2"></i>{{ isset($amenity) ? 'Update' : 'Save' }}</button></div>
        </div>
    </form>
</div>
@endsection
