@extends('admin.layout')
@section('title', 'Edit Project')
@section('page-title', 'Edit Project')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Edit: {{ $project->name }}</h4>
    <a href="{{ route('admin.projects.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back</a>
</div>

<div class="form-card">
    <form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

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
                <input type="text" name="name" class="form-control" value="{{ old('name', $project->name) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $project->slug) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Location *</label>
                <input type="text" name="location" class="form-control" value="{{ old('location', $project->location) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">City *</label>
                <input type="text" name="city" class="form-control" value="{{ old('city', $project->city) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Type *</label>
                <select name="type" class="form-select" required>
                    @foreach(['apartment','villa','plot','commercial'] as $t)
                    <option value="{{ $t }}" {{ old('type', $project->type) == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Status *</label>
                <select name="status" class="form-select" required>
                    @foreach(['ongoing','completed','upcoming'] as $s)
                    <option value="{{ $s }}" {{ old('status', $project->status) == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Total Units</label>
                <input type="number" name="total_units" class="form-control" value="{{ old('total_units', $project->total_units) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Min Price (₹)</label>
                <input type="number" name="price_min" class="form-control" value="{{ old('price_min', $project->price_min) }}" step="0.01">
            </div>
            <div class="col-md-6">
                <label class="form-label">Max Price (₹)</label>
                <input type="number" name="price_max" class="form-control" value="{{ old('price_max', $project->price_max) }}" step="0.01">
            </div>
            <div class="col-md-4">
                <label class="form-label">BHK Options</label>
                <input type="text" name="bhk_options" class="form-control" value="{{ old('bhk_options', $project->bhk_options) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Min Area (sq.ft)</label>
                <input type="number" name="area_min" class="form-control" value="{{ old('area_min', $project->area_min) }}" step="0.01">
            </div>
            <div class="col-md-4">
                <label class="form-label">Max Area (sq.ft)</label>
                <input type="number" name="area_max" class="form-control" value="{{ old('area_max', $project->area_max) }}" step="0.01">
            </div>
            <div class="col-md-6">
                <label class="form-label">RERA ID</label>
                <input type="text" name="rera_id" class="form-control" value="{{ old('rera_id', $project->rera_id) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Possession Date</label>
                <input type="date" name="possession_date" class="form-control" value="{{ old('possession_date', $project->possession_date?->format('Y-m-d')) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Featured Image</label>
                @if($project->featured_image)
                <div class="mb-2"><img src="{{ upload_url($project->featured_image) }}" style="max-height:100px;" class="rounded"></div>
                @endif
                <input type="file" name="featured_image" class="form-control" accept="image/*">
            </div>
            <div class="col-md-6">
                <label class="form-label">Brochure (PDF)</label>
                @if($project->brochure)
                <div class="mb-2"><a href="{{ upload_url($project->brochure) }}" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-file-pdf me-1"></i>Current Brochure</a></div>
                @endif
                <input type="file" name="brochure" class="form-control" accept="application/pdf">
            </div>
            <div class="col-12">
                <label class="form-label">Short Description *</label>
                <textarea name="short_description" class="form-control" rows="2" required>{{ old('short_description', $project->short_description) }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Full Description *</label>
                <textarea name="description" class="form-control" rows="5" required>{{ old('description', $project->description) }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Video URL</label>
                <input type="url" name="video_url" class="form-control" value="{{ old('video_url', $project->video_url) }}">
            </div>
            <div class="col-12">
                <label class="form-label">Add More Gallery Images</label>
                <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
            </div>
            @if(count($project->gallery ?? []) > 0)
            <div class="col-12">
                <label class="form-label">Current Gallery</label>
                <div class="row g-2">
                    @foreach($project->gallery as $idx => $img)
                    <div class="col-2 position-relative" id="gallery-img-{{ $idx }}">
                        <img src="{{ upload_url($img) }}" class="w-100 rounded" style="height:80px;object-fit:cover;">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1" style="font-size:10px;padding:2px 6px;" onclick="deleteGalleryImage({{ $project->id }}, {{ $idx }})"><i class="fas fa-times"></i></button>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            <div class="col-md-6">
                <label class="form-label">Latitude</label>
                <input type="number" name="latitude" class="form-control" value="{{ old('latitude', $project->latitude) }}" step="0.0000001">
            </div>
            <div class="col-md-6">
                <label class="form-label">Longitude</label>
                <input type="number" name="longitude" class="form-control" value="{{ old('longitude', $project->longitude) }}" step="0.0000001">
            </div>
            <div class="col-md-4">
                <div class="form-check mt-2">
                    <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="featured" {{ old('is_featured', $project->is_featured) ? 'checked' : '' }}>
                    <label class="form-check-label" for="featured">Featured Project</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check mt-2">
                    <input type="checkbox" name="is_active" value="1" class="form-check-input" id="active" {{ old('is_active', $project->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="active">Active</label>
                </div>
            </div>
            <div class="col-12 mt-4">
                <button type="submit" class="btn btn-sm-primary px-4"><i class="fas fa-save me-2"></i>Update Project</button>
                <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary ms-2">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
function deleteGalleryImage(projectId, index) {
    if (!confirm('Delete this image?')) return;
    fetch(`{{ url('admin/projects') }}/${projectId}/gallery/${index}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content || '{{ csrf_token() }}' }
    }).then(r => r.json()).then(data => {
        if (data.success) document.getElementById('gallery-img-' + index).remove();
    });
}
</script>
@endsection
