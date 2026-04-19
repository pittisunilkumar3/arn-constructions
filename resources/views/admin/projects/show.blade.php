@extends('admin.layout')
@section('title', 'Project Details')
@section('page-title', 'Project Details: ' . $project->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ $project->name }}</h4>
    <div>
        <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-sm btn-sm-primary"><i class="fas fa-edit me-1"></i>Edit</a>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left me-1"></i>Back</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="form-card mb-4">
            <h5 class="mb-3">Project Information</h5>
            <table class="table">
                <tr><td class="fw-bold" width="200">Name</td><td>{{ $project->name }}</td></tr>
                <tr><td class="fw-bold">Location</td><td>{{ $project->location }}, {{ $project->city }}</td></tr>
                <tr><td class="fw-bold">Type / Status</td><td class="text-capitalize">{{ $project->type }} / <span class="badge bg-{{ $project->status == 'ongoing' ? 'success' : 'primary' }}">{{ ucfirst($project->status) }}</span></td></tr>
                <tr><td class="fw-bold">Price Range</td><td style="color:var(--primary);">{{ $project->price_range }}</td></tr>
                <tr><td class="fw-bold">Area Range</td><td>{{ $project->area_range ?: 'N/A' }}</td></tr>
                <tr><td class="fw-bold">BHK Options</td><td>{{ $project->bhk_options ? str_replace(',', ', ', $project->bhk_options) . ' BHK' : 'N/A' }}</td></tr>
                <tr><td class="fw-bold">Total Units</td><td>{{ $project->total_units ?: 'N/A' }}</td></tr>
                <tr><td class="fw-bold">RERA ID</td><td>{{ $project->rera_id ?: 'N/A' }}</td></tr>
                <tr><td class="fw-bold">Possession</td><td>{{ $project->possession_date?->format('M Y') ?? 'N/A' }}</td></tr>
            </table>
        </div>
        <div class="form-card mb-4">
            <h5 class="mb-3">Description</h5>
            {!! $project->description !!}
        </div>
    </div>
    <div class="col-lg-4">
        @if($project->featured_image)
        <div class="form-card mb-4">
            <h5 class="mb-3">Featured Image</h5>
            <img src="{{ upload_url($project->featured_image) }}" class="w-100 rounded">
        </div>
        @endif
        <div class="form-card mb-4">
            <h5 class="mb-3">Related Data</h5>
            <p><strong>Amenities:</strong> {{ $project->amenities->count() }}</p>
            <p><strong>Floor Plans:</strong> {{ $project->floorPlans->count() }}</p>
            <p><strong>Gallery Images:</strong> {{ $project->galleries->count() }}</p>
            <p><strong>Enquiries:</strong> {{ $project->enquiries->count() }}</p>
        </div>
    </div>
</div>
@endsection
