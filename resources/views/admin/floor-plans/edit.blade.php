@extends('admin.layout')
@section('title', 'Floor Plan Form')
@section('page-title', '{{ isset($floorPlan) ? "Edit" : "Add" }} Floor Plan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">{{ isset($floorPlan) ? 'Edit' : 'Add' }} Floor Plan</h4>
    <a href="{{ route('admin.floor-plans.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back</a>
</div>
<div class="form-card">
    <form action="{{ isset($floorPlan) ? route('admin.floor-plans.update', $floorPlan) : route('admin.floor-plans.store') }}" method="POST" enctype="multipart/form-data">
        @csrf @if(isset($floorPlan)) @method('PUT') @endif
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Project *</label>
                <select name="project_id" class="form-select" required>
                    @foreach($projects as $p)<option value="{{ $p->id }}" {{ old('project_id', isset($floorPlan) ? $floorPlan->project_id : '') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-6"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" value="{{ old('name', isset($floorPlan) ? $floorPlan->name : '') }}" placeholder="e.g., 3 BHK Deluxe" required></div>
            <div class="col-md-4"><label class="form-label">BHK Type *</label><input type="text" name="bhk_type" class="form-control" value="{{ old('bhk_type', isset($floorPlan) ? $floorPlan->bhk_type : '') }}" placeholder="e.g., 3 BHK" required></div>
            <div class="col-md-4"><label class="form-label">Area (sq.ft) *</label><input type="number" name="area_sqft" class="form-control" value="{{ old('area_sqft', isset($floorPlan) ? $floorPlan->area_sqft : '') }}" step="0.01" required></div>
            <div class="col-md-4"><label class="form-label">Price (₹)</label><input type="number" name="price" class="form-control" value="{{ old('price', isset($floorPlan) ? $floorPlan->price : '') }}" step="0.01"></div>
            <div class="col-md-6"><label class="form-label">Floor Plan Image</label>
                @if(isset($floorPlan) && $floorPlan->image)<div class="mb-2"><img src="{{ Storage::url($floorPlan->image) }}" style="max-height:100px;" class="rounded"></div>@endif
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <div class="col-md-3"><label class="form-label">Sort Order</label><input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', isset($floorPlan) ? $floorPlan->sort_order : 0) }}"></div>
            <div class="col-md-3"><div class="form-check mt-4"><input type="checkbox" name="is_active" value="1" class="form-check-input" {{ old('is_active', isset($floorPlan) ? $floorPlan->is_active : true) ? 'checked' : '' }}><label class="form-check-label">Active</label></div></div>
            <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3">{{ old('description', isset($floorPlan) ? $floorPlan->description : '') }}</textarea></div>
            <div class="col-12 mt-3"><button type="submit" class="btn btn-sm-primary px-4"><i class="fas fa-save me-2"></i>{{ isset($floorPlan) ? 'Update' : 'Save' }}</button></div>
        </div>
    </form>
</div>
@endsection
