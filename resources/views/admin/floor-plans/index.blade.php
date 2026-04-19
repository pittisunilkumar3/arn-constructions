@extends('admin.layout')
@section('title', 'Floor Plans')
@section('page-title', 'Manage Floor Plans')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Floor Plans</h4>
    <a href="{{ route('admin.floor-plans.create') }}" class="btn btn-sm-primary"><i class="fas fa-plus me-2"></i>Add Floor Plan</a>
</div>
<div class="data-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead><tr><th>#</th><th>Project</th><th>Name</th><th>BHK Type</th><th>Area (sq.ft)</th><th>Price</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($floorPlans as $i => $fp)
                <tr>
                    <td>{{ $floorPlans->firstItem() + $i }}</td>
                    <td>{{ $fp->project->name }}</td>
                    <td><strong>{{ $fp->name }}</strong></td>
                    <td>{{ $fp->bhk_type }}</td>
                    <td>{{ number_format($fp->area_sqft) }}</td>
                    <td>{{ $fp->price ? '₹' . number_format($fp->price) : '-' }}</td>
                    <td>
                        <a href="{{ route('admin.floor-plans.edit', $fp) }}" class="btn btn-sm btn-sm-primary btn-action"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.floor-plans.destroy', $fp) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')<button class="btn btn-sm btn-danger btn-action"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $floorPlans->withQueryString()->links() }}
</div>
@endsection
