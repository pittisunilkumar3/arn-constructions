@extends('admin.layout')
@section('title', 'Create Project')
@section('page-title', 'Add New Project')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Add New Project</h4>
    <a href="{{ route('admin.projects.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back</a>
</div>

<div class="form-card">
    <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Project Name *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug') }}" placeholder="Auto-generated if empty">
            </div>
            <div class="col-md-6">
                <label class="form-label">Location *</label>
                <input type="text" name="location" class="form-control" value="{{ old('location') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">City *</label>
                <input type="text" name="city" class="form-control" value="{{ old('city') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Type *</label>
                <select name="type" class="form-select" required>
                    <option value="apartment" {{ old('type') == 'apartment' ? 'selected' : '' }}>Apartment</option>
                    <option value="villa" {{ old('type') == 'villa' ? 'selected' : '' }}>Villa</option>
                    <option value="plot" {{ old('type') == 'plot' ? 'selected' : '' }}>Plot</option>
                    <option value="commercial" {{ old('type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Status *</label>
                <select name="status" class="form-select" required>
                    <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Total Units</label>
                <input type="number" name="total_units" class="form-control" value="{{ old('total_units') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Min Price (₹)</label>
                <input type="number" name="price_min" class="form-control" value="{{ old('price_min') }}" step="0.01">
            </div>
            <div class="col-md-6">
                <label class="form-label">Max Price (₹)</label>
                <input type="number" name="price_max" class="form-control" value="{{ old('price_max') }}" step="0.01">
            </div>
            <div class="col-md-4">
                <label class="form-label">BHK Options</label>
                <input type="text" name="bhk_options" class="form-control" value="{{ old('bhk_options') }}" placeholder="e.g., 2,3,4">
            </div>
            <div class="col-md-4">
                <label class="form-label">Min Area (sq.ft)</label>
                <input type="number" name="area_min" class="form-control" value="{{ old('area_min') }}" step="0.01">
            </div>
            <div class="col-md-4">
                <label class="form-label">Max Area (sq.ft)</label>
                <input type="number" name="area_max" class="form-control" value="{{ old('area_max') }}" step="0.01">
            </div>
            <div class="col-md-6">
                <label class="form-label">RERA ID</label>
                <input type="text" name="rera_id" class="form-control" value="{{ old('rera_id') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Possession Date</label>
                <input type="date" name="possession_date" class="form-control" value="{{ old('possession_date') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Featured Image</label>
                <input type="file" name="featured_image" class="form-control" accept="image/*">
            </div>
            <div class="col-md-6">
                <label class="form-label">Brochure (PDF)</label>
                <input type="file" name="brochure" class="form-control" accept="application/pdf">
            </div>
            <div class="col-12">
                <label class="form-label">Short Description *</label>
                <textarea name="short_description" class="form-control" rows="2" required>{{ old('short_description') }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Full Description *</label>
                <textarea name="description" class="form-control" rows="5" required>{{ old('description') }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Video URL</label>
                <input type="url" name="video_url" class="form-control" value="{{ old('video_url') }}">
            </div>
            <div class="col-12">
                <label class="form-label">Gallery Images</label>
                <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
            </div>
            <div class="col-md-6">
                <label class="form-label">Latitude</label>
                <input type="number" name="latitude" class="form-control" value="{{ old('latitude') }}" step="0.0000001">
            </div>
            <div class="col-md-6">
                <label class="form-label">Longitude</label>
                <input type="number" name="longitude" class="form-control" value="{{ old('longitude') }}" step="0.0000001">
            </div>
            <div class="col-md-4">
                <div class="form-check mt-2">
                    <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="featured" {{ old('is_featured') ? 'checked' : '' }}>
                    <label class="form-check-label" for="featured">Featured Project</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check mt-2">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="active" checked>
                    <label class="form-check-label" for="active">Active</label>
                </div>
            </div>
            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-sm-primary px-4"><i class="fas fa-save me-2"></i>Create Project</button>
                <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary ms-2">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection
